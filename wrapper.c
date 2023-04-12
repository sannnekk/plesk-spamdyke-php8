/*
Original-Source Copyright grafxsoftware.com
Modifications by haggybear.de 

gcc wrapper_scp.c -o wrapper
strip wrapper

Nachdem das erledigt ist, tue folgendes:
chmod 4755 wrapper
chown root.root wrapper
*/

/*
Man sollte noch �berpr�fungen einbauen f�r die Return Codes von ALLEN benutzen
Funtionen im wrapper!
Was w�re wenn "setuid()" fehlschl�gt? Der wrapper gibt nix zur�ck!!
*/

#include <stdlib.h>
#include <unistd.h>
#include <stdio.h>
#include <string.h>
#include <sys/types.h>
#include <pwd.h>
#include <grp.h>

#define MAXARG 10
#define STRSIZE 200
int debug_mode=0;
FILE *logfile=0;

int is_root_psaadm(){
    struct passwd *pw;
    struct group *gr;
    char *uname,*gname;
    int uid=getuid();
    int gid=getgid();

    // Hole Infos von User (UIN)
    pw=getpwuid(uid);
    if (pw==NULL) {
	fprintf(logfile,"ERROR: Could not get the uid\n");
	return(-1);
    };
    uname=pw->pw_name;

    //Hole Info der Gruppe
    gr=getgrgid(gid);
    if (gr==NULL){
	fprintf(logfile,"ERROR: Could not get the gid\n");
	return(-1);
    };
    gname=gr->gr_name;
    if (((strcmp(uname,"psaadm")==0)&&((strcmp(gname,"sw-cp-server")==0)||(strcmp(gname,"psaadm")==0)))||((strcmp(uname,"root")==0)||(strcmp(gname,"root")==0))) return(1);
    fprintf(logfile,"ERROR: uname:gname should be psaadm:sw-cp-server(or psaadm) (now they are %s:%s)\n",uname,gname);
    return(-1);
};

int main (int argc, char **argv) {
    int n,i,c,result;
    char *args[MAXARG];
    char arg[STRSIZE+1];arg[STRSIZE]='\0';
    FILE *tmp;
    if (argc<3) return;
    c=0;
    logfile=stdout;
    if (argv[1][0]=='-'){
	if (argv[1][1]=='d') {
	    c=1;
	    debug_mode=1;
	    tmp=fopen("/tmp/wrapper.log","a");
	    if (tmp) logfile=tmp;
	};
    };
    n=argv[c+1][0]-'0';

    if (is_root_psaadm()<0) return(1);

    switch(n){
	case 1: strcpy(arg,"./scp_do_write.sh");
	    break;
	case 2: strcpy(arg,"./scp_reserved.sh");
	    break;
	case 3: strcpy(arg,"./updater.sh");
	    break;
	default:
		fprintf(logfile,"ERROR: bad command\n");
		exit(1);
	    break;
    };
     for (i=0;i<MAXARG;i++) args[i]=NULL;

    for (i=0;i<MAXARG;i++){
	if ((argv[i+2+c]==NULL)||(i>=argc)) break;
	args[i]=argv[i+2+c];
    };
    args[MAXARG-1]=NULL;

    setuid(0);

#if ( MAXARG < 10 )
#error Die execl line muss angepasst werden an die Anzahl der Parameter
#endif


    if (debug_mode){
	fprintf(logfile,"CMDLINE: %s ",arg);
        for (i=0;i<MAXARG;i++){
	    if (args[i]) fprintf(logfile,"%s ",args[i]);
	};
	fprintf(logfile,"\n");
    };
    fflush(logfile);

    result=execl("/bin/bash","/bin/bash",arg,args[0],args[1],args[2],args[3],args[4],args[5],args[6],args[7],args[8],NULL);
    fclose(logfile);
};
