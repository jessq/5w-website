#include <stdio.h>
#include <stdlib.h>
#include <ctype.h>

#include "CGIparse.h"

int asciihextoint(char a);

/* returns NULL on failure */

CGIparse CGIparse_create(char *data_in)
{
  CGIparse cgip;
  int len, readmark,writemark,startmark;

  if ((cgip=(CGIparse) malloc(sizeof(CGIparseRecord)))==NULL)
    return NULL;

  /* copy the data */
  len=strlen(data_in);

  cgip->data=malloc(len+1);
  memcpy(cgip->data,data_in,len+1);

  cgip->key=Vector_create(0);
  cgip->value=Vector_create(0);

  writemark=0;
  startmark=0;

  /* convert all escape sequences into real ASCII and rip out the useful fields */

  if (len==0)
    return cgip;

  for (readmark=0;readmark<=len;readmark++)
    {
      switch (cgip->data[readmark])
	{
	case '=':
	  cgip->data[writemark++]='\0';
	  Vector_add(cgip->key,&cgip->data[startmark]);
	  startmark=writemark;
	  break;

	case '\0':
	case '&':
	  cgip->data[writemark++]='\0';
	  Vector_add(cgip->value,&cgip->data[startmark]);
	  startmark=writemark;
	  break;

	case '+':
	  cgip->data[writemark++]=' ';
	  break;
	 
	case '%':
	  if ((readmark+2)<len)
	    {
	      cgip->data[writemark++]=asciihextoint(cgip->data[readmark+1])*16+
		asciihextoint(cgip->data[readmark+2]);
	      readmark+=2;
	    }
	  break;

	default:
	  cgip->data[writemark++]=cgip->data[readmark];
	}
    }

  cgip->data[writemark]=0;

  if (Vector_getSize(cgip->key)!=Vector_getSize(cgip->value))
    {
      printf("different number of keys and values!");
      CGIparse_free(cgip);
      return NULL;
    }

  return cgip;
}

int CGIparse_pairs(CGIparse cgip)
{
  return (Vector_getSize(cgip->key));
}

int asciihextoint(char a)
{
a=tolower(a);

if (a<='9')
  return (a-'0');

return (a-'a'+10);
}

void CGIparse_free(CGIparse cgip)
{
  Vector_free(cgip->key);
  Vector_free(cgip->value);

  free(cgip->data);
  free(cgip);
}

char* CGIparse_key(CGIparse cgip, int index)
{
  return Vector_elementAt(cgip->key,index);
}

char* CGIparse_value(CGIparse cgip, int index)
{
  return Vector_elementAt(cgip->value,index);
}
