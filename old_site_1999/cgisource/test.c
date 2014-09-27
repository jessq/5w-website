#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <ctype.h>

#define BUFFSIZE 4096
#define MAXFIELDS 15
#define MAXROWS  200
#define FILENAME "people.tsv"

typedef struct {
  char *field[MAXFIELDS];
} Row;

/******************************************
* PROTOTYPES
******************************************/
void insertfile(char filename[]);
int parse(char buffer[], Row *row);
int sortfunction(const void *, const void *);
int intcompare(char a[], char b[]);

/******************************************
* GLOBALS
******************************************/
Row rows[MAXROWS];
Row headings;

int sortbyfield=0;

/******************************************
* MAIN
******************************************/
int main(int argc, char* argv[])
{
  FILE *file;
  char buffer[BUFFSIZE];
  int row;
  int numfields;
  int a,b;
  int c,d,count;

  printf("Content-type: text/html\n\n");

  insertfile("pre.html");

  file=fopen(FILENAME,"r");
  if (file==NULL)
    goto cleanup;

  fgets(buffer,BUFFSIZE,file);
  numfields=parse(buffer,&headings);

  row=0;

  while (fgets(buffer,BUFFSIZE,file))
    {
      parse(buffer,&rows[row]);
      row++;
    }

  fclose(file);

  printf("<TABLE WIDTH=100%%>\n");

  for (b=0;b<numfields;b++)
    {
      printf("<TD><B><A HREF=\"search.cgi?%s\">%s</A></B></TD>",headings.field[b],headings.field[b]);
      if (argc==2 && !strncmp(argv[1],headings.field[b],strlen(argv[1])))
	sortbyfield=b;
    }

  /* sort the data now */

  qsort(rows,row,sizeof(Row),sortfunction);

  /* display the results */

  for (a=0;a<row;a++)
    {
      printf("<TR>");
      for (b=0;b<numfields;b++)
	{
	  if (rows[a].field[b][0]=='~')
	   {
	     printf("<TD></TD>");
	     continue;
	   }

	  if (rows[a].field[b][0]=='\"')
	    {
	      c=strlen(rows[a].field[b]);
	      rows[a].field[b][c-1]='\0';

	      printf("<TD>%s</TD>",&rows[a].field[b][1]);
	      continue;
	    }

	  if (!strncmp("://",&rows[a].field[b][4],3))
	    {
	      printf("<TD><A HREF=\"%s\">www page</A></TD>",rows[a].field[b]);
	      continue;
	    }

	  c=strlen(rows[a].field[b]);
	  count=0;
	  for (d=0;d<c;d++)
	    {
	      if (rows[a].field[b][d]=='@')
		count++;
	    }

	  if (count==1)
	    {
	      printf("<TD><A HREF=\"mailto:%s\">%s</A>",rows[a].field[b],rows[a].field[b]);
	      continue;
	    }

	  printf("<TD>%s</TD>",rows[a].field[b]);
	}
      printf("</TR>\n");
    }
  printf("</TABLE>\n");

cleanup:

  insertfile("post.html");
}

/*******************************************
* PARSE
*******************************************/
int parse(char buffer[], Row *row)
{
  int len=strlen(buffer);
  int pos=0;
  int start=0;
  int fieldnumber=0;

  for (pos=0;pos<len && fieldnumber<MAXFIELDS;pos++)
    {
      if (buffer[pos]=='\t' || buffer[pos]=='\n' || pos==(len-1))
	{
	  /* suck out the stuff between tabs! */
	  row->field[fieldnumber]=malloc(pos-start+2);
	  memcpy(row->field[fieldnumber],&buffer[start],pos-start+1);
	  if (pos==(len-1))
	    row->field[fieldnumber][pos-start+1]='\0';
	  else
	    row->field[fieldnumber][pos-start]='\0';

	  /* get ready for the next one */
	  fieldnumber++;
	  start=pos+1;
	}
    }
  return (fieldnumber);
}

/******************************************
* INSERTFILE
*******************************************/
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
  } while (len==BUFFSIZE);
  
  fclose(file);
  
}

/******************************************
* SORTFUNCTION
*******************************************/
int sortfunction(const void *ar,const void *br)
{
Row *a=(Row*) ar;
Row *b=(Row*) br;
int blah;
int ai,bi;
int len;

ai=atoi(a->field[sortbyfield]);
bi=atoi(b->field[sortbyfield]);

if (a->field[sortbyfield][0]=='\0' && b->field[sortbyfield][0]!='\0')
  return (1);

if (a->field[sortbyfield][0]!='\0' && b->field[sortbyfield][0]=='\0')
  return (-1);

if (ai==0 && bi==0)
  return(strcmp(a->field[sortbyfield],b->field[sortbyfield]));

if (ai==bi)
  return (intcompare(a->field[sortbyfield],b->field[sortbyfield]));
    
return (ai-bi);
}

int intcompare(char a[], char b[])
  {
    int ai,bi;
    int apos=0,bpos=0;

    if (a[0]=='\0')
      ai=0;
    else
      ai=atoi(a);
    
    if (b[0]=='\0')
      bi=0;
    else
      bi=atoi(b);

    if (ai!=bi)
      return (ai-bi);

    apos=0;
    bpos=0;

    while (a[apos]!='\0' && isdigit(a[apos]))
      apos++;

    while (a[apos]!='\0' && !isdigit(a[apos]))
      apos++;

    while (b[bpos]!='\0' && isdigit(b[bpos]))
      bpos++;

    while (b[bpos]!='\0' && !isdigit(b[bpos]))
      bpos++;

    if (a[apos]=='\0' && b[bpos]=='\0')
      return (0);

    return (intcompare(&a[apos],&b[bpos]));

  }
