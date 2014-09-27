#include <stdio.h>
#include <stdlib.h>
#include <ctype.h>

#include "tsvdb.h"
#include "CGIparse.h"

#define DB_NAME "people.tsv"
#define HEADER_FILE "header.html"
#define FOOTER_FILE "footer.html"
#define BUFFSIZE 2048

void formatstring(char *format, char *string);
void insertfile(char filename[]);
void stringtolower(char *s);
int columnnametonumber(TSVdb tsvdb, char *name);
int strcmpi(char *s1, char *s2);
void selectnotempty(TSVdb tsvdb,int column);
void mysort(TSVdb tsvdb, int column , int (*compare)(char* a,char* b), int invert);
int numcmp(char *s1, char *s2);
int datecmp(char *s1, char *s2);
void dosort(TSVdb tsvdb, char *value);

int main(int argc, char *argv[])
{
  TSVdb tsvdb;
  CGIparse cgip;
  int rows;
  int row, column;
  char *cell, *format;
  int args;
  int index;
  char *key, *value;

  printf("Content-type: text/html\n\n");

  insertfile(HEADER_FILE);

  tsvdb=TSVdb_create(DB_NAME);
  
  rows=TSVdb_rows(tsvdb);

  /* get the arguments */

  cgip=CGIparse_create(getenv("QUERY_STRING"));

  /* execute the instructions contained in the args */
  args=CGIparse_pairs(cgip);

  for (index=0;index<args;index++)
    {
      key=CGIparse_key(cgip,index);
      value=CGIparse_value(cgip,index);

      stringtolower(key);

      if (!strcmp(key,"sort"))
	{
	  dosort(tsvdb,value);
	}

      if (!strcmp(key,"notempty"))
	{
	  column=columnnametonumber(tsvdb,value);
	  selectnotempty(tsvdb,column);
	}
    }


  rows=TSVdb_rows(tsvdb);

  /* now output the resulting data */

  printf("<TR>");

  for (column=0;column<TSVdb_columns(tsvdb);column++)
    {
      cell=TSVdb_getCell(tsvdb,1,column);
      printf("<TH>%s</TH>",cell);
    }

  printf("</TR>\n");

  for (row=3;row<rows;row++)
    {
      if (row&1)
	printf("<TR class=odd>");
      else
	printf("<TR class=even>");

      for (column=0;column<TSVdb_columns(tsvdb);column++)
	{
	  printf("<TD>");
	  cell=TSVdb_getCell(tsvdb,row,column);
	  format=TSVdb_getCell(tsvdb,2,column);
	  formatstring(format,cell);
	  printf("</TD>");
	}

      printf("</TR>\n");
    }

  insertfile(FOOTER_FILE);

  return 0;
}

void formatstring(char *format, char *string)
{
  int pos,len;
  
  len=strlen(format);

  /* do not do substitution if the cell is empty */
  if (strlen(string)==0)
    {
      printf("&nbsp");
      return;
    }

  for (pos=0;pos<len;pos++)
    {
      if (format[pos]!='^')
	printf("%c",format[pos]);
      else
	printf("%s",string);
    }
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

int columnnametonumber(TSVdb tsvdb, char *name)
{
int columns;
int count;

count=0;
columns=TSVdb_columns(tsvdb);

for (count=0;count<columns;count++)
  {
    if (!strcmpi(name,TSVdb_getCell(tsvdb,0,count)))
      return count;
  }

return -1;
}

void stringtolower(char *s)
{
int pos;

pos=0;

while (s[pos]!='\0')
  {
    s[pos]=tolower(s[pos]);
    pos++;
  }
}

int strcmpi(char *s1, char *s2)
{
int pos=0;
int a,b;

if (s1[0]=='\0' && s2[0]!='\0')
  return 1;

if (s1[0]!='\0' && s2[0]=='\0')
  return -1;

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

int numcmp(char *s1, char *s2)
{
 if (s1[0]=='\0' && s2[0]!='\0')
  return 1;

 if (s1[0]!='\0' && s2[0]=='\0')
   return -1;
 
  return (atoi(s1)-atoi(s2));
}

int datecmp(char *s1, char *s2)
{
  int month1,month2,day1,day2;
  int index;

 if (s1[0]=='\0' && s2[0]!='\0')
  return 1;

 if (s1[0]!='\0' && s2[0]=='\0')
   return -1;
 
  day1=0;
  day2=0;

  month1=atoi(s1);
  month2=atoi(s2);

  index=0;

  while (s1[index]!='\0')
    {
      if (s1[index]=='/')
	{
	  day1=atoi(&s1[index+1]);
	  break;
	}
      index++;
    }

  index=0;

  while (s2[index]!='\0')
    {
      if (s2[index]=='/')
	{
	  day2=atoi(&s2[index+1]);
	  break;
	}
      index++;
    }

  return (month1*32+day1-month2*32-day2);
}

void mysort(TSVdb tsvdb, int column, int (*compare)(char* a,char* b), int invert)
{
  int rows;
  char *cella, *cellb;
  int index;
  int changed;

  if (invert>0)
    invert=-1;
  else
    invert=1;

  rows=TSVdb_rows(tsvdb);

  do {
    changed=0;
    
    for (index=3;index<(rows-1);index++)
      {
	cella=TSVdb_getCell(tsvdb,index,column);
	cellb=TSVdb_getCell(tsvdb,index+1,column);

	if ((invert*compare(cella,cellb))>0)
	  {
	    TSVdb_swapRows(tsvdb,index,index+1);
	    changed=1;
	  }
      }
  } while (changed!=0);
}

void selectnotempty(TSVdb tsvdb,int column)
{
  int index;
  char *cell;

  index=0;

  while (index<TSVdb_rows(tsvdb))
    {
      cell=TSVdb_getCell(tsvdb,index,column);
      
      if (strlen(cell)==0)
	TSVdb_deleteRow(tsvdb,index);
      else
	index++;
    }
}

void dosort(TSVdb tsvdb, char *value)
{
  char *field, *sorttype;
  int pos;
  int column;

  sorttype=value;
  
  pos=0;
  field=NULL;

  while (value[pos]!='\0')
    {
      if (value[pos]==' ')
	{
	  value[pos]='\0';
	  field=&value[pos+1];
	  break;
	}
      pos++;
    }

  if (field==NULL)
    return;

  stringtolower(sorttype);
  column=columnnametonumber(tsvdb,field);

  if (!strcmp(sorttype,"az"))
    {
      mysort(tsvdb,column,strcmpi,0);
    }
  
  if (!strcmp(sorttype,"09"))
    {
      mysort(tsvdb,column,numcmp,0);
    }
  
  if (!strcmp(sorttype,"za"))
    {
      mysort(tsvdb,column,strcmpi,1);
    }
  
  if (!strcmp(sorttype,"90"))
    {
      mysort(tsvdb,column,numcmp,1);
    }
  
  if (!strcmp(sorttype,"date"))
    {
      mysort(tsvdb,column,datecmp,0);
    }
}
