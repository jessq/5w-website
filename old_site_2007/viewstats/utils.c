#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <time.h>
#include <stdarg.h>
#include <ctype.h>
#include <sys/stat.h>
#include <unistd.h>

#include "utils.h"

FILE *_logfile=NULL;

int strcmpi(char *s1, char *s2)
{
  int pos=0;
  int a,b;

  while (1)
    {
      a=tolower(s1[pos]);
      b=tolower(s2[pos]);

      if (a<b)
	return -1;
      if (a>b)
	return 1;
      if (a==b && a==0)
	return 0;

      pos++;
    }

  return -1;
}

void Log_error(char *filename, char *fmt, ...)
{
  va_list ap;

  va_start(ap,fmt);

  Log_open(filename);
  Log_vappend(fmt,ap);
  Log_close();
}

int Log_open(char *filename)
{
  if ((_logfile=fopen(filename,"a"))==NULL)
    {
      fprintf(stderr,"Could not open log file for writing %s.\n",filename);
      return -1;
    }
  return 0;
}

int Log_setto(FILE *f)
{
  _logfile=f;
  return 0;
}

void Log_append(char *format, ...)
{
  va_list ap;

  va_start(ap,format);

  Log_vappend(format,ap);
}

void Log_vappend(char *format, va_list ap)
{
  time_t thetime;
  struct tm *tm;

  if (_logfile==NULL)
    return;

  thetime=time(NULL);
  tm=localtime(&thetime);

  fprintf(_logfile,"%02i/%02i/%02i %02i:%02i:%02i -- ",
	  tm->tm_mon,tm->tm_mday,tm->tm_year,
	  tm->tm_hour,tm->tm_min,tm->tm_sec);


  vfprintf(_logfile,format,ap);
  fprintf(_logfile,"\n");
}  

void Log_close()
{
  if (_logfile==NULL)
    fclose(_logfile);
  
  _logfile=NULL;
}

char* freadln(FILE *f)
{
  char *p;
  int size;
  int sizeincrement=128;
  int read=0;

  p=malloc(sizeincrement);
  size=sizeincrement;

  while (1)
    {
      p[read]=fgetc(f);

      if (p[read]==EOF)
	{
	  p[read]='\0';
	  
	  if (read==0)
	    {
	      free(p);
	      p=NULL;
	    }

	  return p;
	}

      if (p[read]=='\n' || p[read]=='\r')
	{
	  p[read]='\0';

	  return p;
	}

      read++;

      if (read>=size)
	{
	  size+=sizeincrement;

	  p=realloc(p,size);
	}
    }

  return p;
}

char* sprintfalloc(char *format, ...)
{
  va_list ap;
  char *p;
  int size;
  int sizeincrement=128;

  va_start(ap,format);

  size=sizeincrement;
  p=malloc(size);

  while (p!=NULL && vsnprintf(p,size-1, format, ap)==-1)
    {
      size+=sizeincrement;
      p=realloc(p,size);
    }

  return p;
}

void insertfile(char filename[])
{
  FILE *file;
  char buffer[BUFFSIZE];
  int len;

  file=fopen(filename,"r");
  if (file==NULL)
    return;

  do {
    len=fread(buffer,1,BUFFSIZE,file);
    fwrite(buffer,1,len,stdout);
  } while (len>0);

  fclose(file);
}

void stolower(char *s)
{
  int len,pos;

  len=strlen(s);
  for (pos=0;pos<len;pos++)
    s[pos]=tolower(s[pos]);
}

char* nexttoken(char *in)
{
  char *tokenstart;
  char *token;
  int len;
  int pos;

  len=strlen(in);
  pos=0;

  while (in[pos]!=0 && isspace(in[pos]))
    pos++;

  tokenstart=&in[pos];
  pos=0;

  if (tokenstart[0]=='\"')
    {
      while (tokenstart[pos]!=0 && tokenstart[pos]!='\"')
	pos++;
    }
  else
    {
      while (tokenstart[pos]!=0 && !isspace(tokenstart[pos]))
	pos++;
    }
  
  token=malloc(pos+1);
  memcpy(token,&tokenstart[pos],pos);
  token[pos]='\0';

  return token;
  
}

long fsize(char *filename)
{
  struct stat finfo;

  stat(filename,&finfo);

  return (finfo.st_size);
}

/* returns the character after the needle in the haystack */
char* strstrend(char *haystack, char *needle)
{
  char *matchstart;
  int needlelen;

  matchstart=strstr(haystack,needle);
  if (matchstart==NULL)
    return NULL;

  needlelen=strlen(needle);

  return (&matchstart[needlelen]);
}

/* compares the suffix of the string */
int strsuffix(char *in, char *suffix)
{
  int len;
  int suffixlen;

  len=strlen(in);
  suffixlen=strlen(suffix);

  return (strcmp(suffix,&in[len-suffixlen]));
}


long safeatol(char *s)
{
  long result;

  if (s==NULL)
    result=-1;
  else
    result=atol(s);

  return result;

}

int safeatoi(char *s)
{
  long result;

  if (s==NULL)
    result=-1;
  else
    result=atoi(s);

  return result;

}
