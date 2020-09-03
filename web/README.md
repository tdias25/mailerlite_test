
# ybank

> ybank - frontend
> 
## Build Setup

```bash

# install dependencies

$ yarn install
# serve with hot reload at localhost:3000
$ yarn dev
# build for production and launch server
$ yarn build

$ yarn start
# generate static project
$ yarn generate
```  
For detailed explanation on how things work, check out [Nuxt.js docs](https://nuxtjs.org).

  
## API Endpoint configuration

After building the NuxtJs project, you have to specific the  ```API Endpoint``` URL in order for the project to work properly.


first, locate the enviroment file: 
```.env```


then change the value of the variable ```API_URL``` to the api url of your choosing. ```(without trailing slash)```

or you can use the default value defined as:
```API_URL="http://127.0.0.1:8000/api"``` (yes, without trailing slash)
