#include <stdio.h>
#include <stdlib.h>
#include <ctype.h>

#include "cgiparse.h"
#include "table.h"
#include "utils.h"

int asciihextoint(char a);

/* create a Table from CGI form data. Note that the memory allocated in this function
   cannot be recovered. */
/* returns NULL on failure */

Table cgiparse()
{
  int len, readmark,writemark,startmark;
  char *key, *value, *data=NULL;
  char *data_in=NULL;
  char *formmethod;
  Table t;

  t=Table_create();
  
  /* what method was used to send the form */
  formmethod=getenv("REQUEST_METHOD");
  
  if (formmethod==NULL)
    return t;

  if (!strcmp(formmethod,"GET"))
    {
      data_in=getenv("QUERY_STRING");
      len=strlen(data_in);

      data=malloc(len+1);
      memcpy(data,data_in,len+1);
    }

  if (!strcmp(formmethod,"POST"))
    {
      len=safeatoi(getenv("CONTENT_LENGTH"));
      if (len<=0)
	return t;
      data_in=malloc(len+1);
      fread(data_in,1,len,stdin);
      data=data_in;
    }

  if (data==NULL)
    return t;

  writemark=0;
  startmark=0;

  /* convert all escape sequences into real ASCII and rip out the useful fields */

  if (len==0)
    return t;

  for (readmark=0;readmark<=len;readmark++)
    {
      switch (data[readmark])
	{
	case '=':
	  data[writemark++]='\0';
	  key=&data[startmark];
	  startmark=writemark;
	  break;

	case '\0':
	case '&':
	  data[writemark++]='\0';
	  value=&data[startmark];
	  startmark=writemark;

	  Table_put(t,key,value);

	  break;

	case '+':
	  data[writemark++]=' ';
	  break;
	 
	case '%':
	  if ((readmark+2)<len)
	    {
	      data[writemark++]=asciihextoint(data[readmark+1])*16+
		asciihextoint(data[readmark+2]);
	      readmark+=2;
	    }
	  break;

	default:
	  data[writemark++]=data[readmark];
	}
    }

  data[writemark]=0;

  return t;
}

int asciihextoint(char a)
{
a=tolower(a);

if (a<='9')
  return (a-'0');

return (a-'a'+10);
}
