#include <stdio.h>
#include <string.h>

#define FILENAME "people.tsv"
#define BUFFLEN 1024
#define MAXFIELDS 50

/* the user doesn't specify the data that we read, so we can
   kind of justify using fixed buffer lengths */

/*************************************************/
int main(int argc, char *argv[])
{
  FILE *f;
  char buffer[BUFFLEN];
  int len;
  int pos;
  
  /* read the data */
  
  f=fopen(FILENAME,"r");
  
  while (fgets(buffer,BUFFLEN,f))
    {
      len=strlen(buffer);
      
      for (pos=0;pos<len;pos++)
	{
	  
	  
	  
	}
    }
}

char** readline(FILE *f)
{
  char *array[];

  array=calloc(sizeof(char*),MAXFIELDS)
  
}

