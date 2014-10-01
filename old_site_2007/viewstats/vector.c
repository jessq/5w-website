#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <pthread.h>

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

  pthread_mutex_init(&v->mutex,NULL);

  return v;
}

/* grows the vector. returns <0 on failure. */

int Vector_grow(Vector v)
{
  int newallocated;
  void** newv;

  pthread_mutex_lock(&v->mutex);

  newallocated=v->allocated+v->allocationchunksize;

  if ((newv=(void**) realloc(v->v,sizeof(void*)*newallocated))==NULL)
    {
      pthread_mutex_unlock(&v->mutex);
      return -1;
    }

  v->v=newv;
  v->allocated=newallocated;

  pthread_mutex_unlock(&v->mutex);
      
  return 0;
      
}

/* returns <0 on failure */

int Vector_add(Vector v, void* p)
{

  /* grow the vector if necessary */

  if (v->size>=v->allocated)
    {
      if (Vector_grow(v)<0)
	return -1;
    }

  pthread_mutex_lock(&v->mutex);

  v->v[v->size]=p;

  v->size++;

  pthread_mutex_unlock(&v->mutex);

  return 0;
}

void* Vector_elementAt(Vector v, int index)
{

  if (index>=v->size || index<0)
    return NULL;

  return(v->v[index]);
}

int Vector_delete(Vector v, int index)
{
  if (index>=v->size || index<0)
    return -1;

  pthread_mutex_lock(&v->mutex);

  memmove(&v->v[index],&v->v[index+1],sizeof(char*)*(v->size-index-1));

  v->size--;

  pthread_mutex_unlock(&v->mutex);

  return 0;
}

int Vector_setElementAt(Vector v, int index, void* p)
{
  if (index>=v->size || index<0)
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

/* do a find using pointer comparisions */
int Vector_find(Vector v, void *p)
{
  int size;
  int idx;

  size=Vector_getSize(v);

  
  for (idx=0;idx<size;idx++)
    {
      if (Vector_elementAt(v,idx)==p)
	return idx;
    }
  return -1;
}

/* do a find using string comparisons */
int Vector_finds(Vector v, char *s)
{
  int size;
  int idx;

  size=Vector_getSize(v);

  for (idx=0;idx<size;idx++)
    {
      if (!strcmp(Vector_elementAt(v,idx),s))
	return idx;
    }
  return -1;
}

void Vector_sort(Vector v, int (*compar)(const void *, const void *))
{
  qsort(v->v,v->size,sizeof(char*),compar);
}
