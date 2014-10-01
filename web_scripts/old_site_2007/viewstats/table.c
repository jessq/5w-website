#include <stdlib.h>

#include "table.h"
#include "vector.h"

Table Table_create()
{
  Table t;

  t=malloc(sizeof(TableRecord));
  t->keys=Vector_create(0);
  t->values=Vector_create(0);

  return t;
}

void Table_put(Table t, void *key, void *value)
{
  int idx;

  idx=Vector_find(t->keys, key);

  /* already exists? overwrite */
  if (idx>=0)
    {
      Vector_setElementAt(t->keys,idx,key);
      Vector_setElementAt(t->values,idx,value);

      return;
    }

  Vector_add(t->keys,key);
  Vector_add(t->values,value);      
  
}

void Table_puts(Table t, char *key, void *value)
{
  int idx;

  idx=Vector_finds(t->keys, key);

  /* already exists? overwrite */
  if (idx>=0)
    {
      Vector_setElementAt(t->keys,idx,key);
      Vector_setElementAt(t->values,idx,value);

      return;
    }

  Vector_add(t->keys,key);
  Vector_add(t->values,value);      
  
}

void* Table_get(Table t, void *key)
{
  int idx;

  idx=Vector_find(t->keys,key);
  
  if (idx<0)
    return NULL;

  return (Vector_elementAt(t->values,idx));
}

/* returns the value of the deleted pair */
void* Table_remove(Table t, void *key)
{
  int idx;
  void *value;

  idx=Vector_find(t->keys,key);

  if (idx<0)
    return NULL;

  value=Vector_elementAt(t->values,idx);
  Vector_delete(t->keys,idx);
  Vector_delete(t->values,idx);

  return (value);
}

/* returns the value of the deleted pair */
void* Table_removes(Table t, char *key)
{
  int idx;
  void *value;

  idx=Vector_find(t->keys,key);

  if (idx<0)
    return NULL;

  value=Vector_elementAt(t->values,idx);
  Vector_delete(t->keys,idx);
  Vector_delete(t->values,idx);

  return (value);
}

int Table_pairs(Table t)
{
  return Vector_getSize(t->keys);
}

void Table_free(Table t)
{
  Vector_free(t->keys);
  Vector_free(t->values);
  free(t);

}

void* Table_getKey(Table t, int idx)
{
  return Vector_elementAt(t->keys,idx);
}

void* Table_getValue(Table t, int idx)
{
  return Vector_elementAt(t->values,idx);
}

/* do a lookup using string comparisions on key instead of pointer comparisions */
char* Table_gets(Table t, char *key)
{
  int idx;
  int pairs;
  char *foundkey;

  pairs=Table_pairs(t);

  for (idx=0;idx<pairs;idx++)
    {
      foundkey=Table_getKey(t,idx);
      if (!strcmp(key,foundkey))
	return Table_getValue(t,idx);
    }

  return NULL;
}





