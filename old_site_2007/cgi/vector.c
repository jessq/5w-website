#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include "vector.h"

/* Vector Class
   
   Edwin Olson, eolson@mit.edu
   
   A simple variably-sized array, much like Vector in Java. Each element
   is a char*.
   
   */

#define DEFAULT_ALLOCATION_CHUNK_SIZE 128

/* create a new vector. returns NULL on failure. */

Vector Vector_create(int allocationchunksize)
{
  Vector v;

  if ((v=(Vector) malloc(sizeof(VectorRecord)))==NULL)
    return NULL;

  v->v=NULL;
  v->allocated=0;
  v->size=0;

  if (allocationchunksize>0)
    v->allocationchunksize=allocationchunksize;
  else
    v->allocationchunksize=DEFAULT_ALLOCATION_CHUNK_SIZE;

  return v;
}

/* grows the vector. returns <0 on failure. */

int Vector_grow(Vector v)
{
  int newallocated;
  char** newv;

  newallocated=v->allocated+v->allocationchunksize;

  if ((newv=(char**) realloc(v->v,sizeof(char*)*newallocated))==NULL)
    return -1;
  
  v->v=newv;
  v->allocated=newallocated;

  return 0;
      
}

/* returns <0 on failure */

int Vector_add(Vector v, char* p)
{

  /* grow the vector if necessary */

  if (v->size>=v->allocated)
    {
      if (Vector_grow(v)<0)
	return -1;
    }

  v->v[v->size]=p;

  v->size++;

  return 0;
}

char* Vector_elementAt(Vector v, int index)
{

  if (index>=v->size)
    return NULL;

  return(v->v[index]);
}

int Vector_delete(Vector v, int index)
{
  if (index>=v->size)
    return -1;

  memmove(&v->v[index],&v->v[index+1],sizeof(char*)*(v->size-index-1));
  v->size--;

return 0;
}

int Vector_setElementAt(Vector v, int index, char* p)
{
  if (index>=v->size)
    return -1;
  
  v->v[index]=p;

  return 0;
}

int Vector_getSize(Vector v)
{
  return (v->size);
}

void Vector_free(Vector v)
{
  free(v->v);
  v->allocated=0;
  v->size=0;
  free(v);
}

