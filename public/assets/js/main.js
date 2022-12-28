
var form=document.getElementById('formulariodni')

var btn_enviar=document.getElementById('btn_enviar')
var loader_box=document.getElementById('loader')

var datodni=''

form.addEventListener('submit',e=>{
    e.preventDefault();
    datodni = Object.fromEntries(new FormData(e.target));
    console.log(JSON.stringify(datodni))
    validardni();
    btn_enviar.disabled=true;
    btn_enviar.classList.add('btn_inicio_hover');
    loader_box.innerHTML="<span class='spinner-border spinner-border-sm' role='status' aria-hidden='true'></span> Cargando" 
})
     
function validardni(){ 
    axios.post('/api/obtenerdatos',datodni).then(function (response) {
        window.location.href = '/inicio';
        console.log(response.data);
        loader_box.innerHTML='Enviar'  
        btn_enviar.disabled=false;
        btn_enviar.classList.remove('btn_inicio_hover');
        })
        .catch(function (error) {
        Swal.fire({icon: 'error',text:error.response.data.message})
        console.log(error.response.data.message);
        loader_box.innerHTML='Enviar'  
        btn_enviar.disabled=false; 
        btn_enviar.classList.remove('btn_inicio_hover');      
        });      
      
}

var form_respuesta=[];
function enviar(p,s,m,o,tipo){
    /*
    p=pregunta
    s=submodulo
    m=modulo
    o=respuesta
    */
    console.log(p+' '+o+' '+s+' '+m)
    enviar_respuestas(p,o,m,s,tipo);
    //rellenar_form(p,o)
}

function enviar_observacion(p,s,m,input){
    let obs=input.value.trim()
    if(obs!=''){
        console.log(p+' '+s+' '+m+' '+obs)
        enviar_respuesta_observacion(p,s,m,obs,2)
    }
    
}




var resultado=''
var observacion=''
var pregunta=''
function enviar_observacion_con_re(p,s,m,input,res){
    let obs=input.value.trim()
    if(obs!=''){

        if(pregunta==''){
            pregunta=p;
        }else{
          if(pregunta!=p){
            resultado='',
            observacion='',
            pregunta=p;
          }  
        }
        observacion=obs;
        if(resultado==''){
            resultado=res;
        }
        
        enviar_ambos_resultado(p,s,m)
    }
}
function enviar_opcion_con_re(p,s,m,o,obser){

    if( pregunta==''){
        pregunta=p;
    }else{
        if(pregunta!=p){
            resultado='',
            observacion='',
     
            pregunta=p;
        }  
    }
    resultado=o;
    if(observacion==''){
        observacion=obser;
    }
    
    enviar_ambos_resultado(p,s,m)
}

function enviar_respuesta_observacion(p,s,m,obs,tipo){ 
    let form={
        _csrf:window.CSRF_TOKEN,
        pregunta:p,
        respuesta:'',
        modulo:m,
        submodulo:s,
        observacion:obs,
        tipo:tipo, //1 es tipo de respuesta de opcion, 2 es texto y 3 es ambos
    }
    console.log(form)
    axios.post('/api/enviarrespuesta_pregu', form).then(function (response) {
        if(response.status==200 || response.status==201){
            console.log(response.data)
        }else{
            Swal.fire({icon: 'error',text:'Algo salió mal'})
        }      
    })
    .catch(function (error) {
         Swal.fire({icon: 'error',text:error.response.data.message})
    }); 
}

function enviar_respuestas(p,o,m,s,tipo){ 
    let form={
        _csrf:window.CSRF_TOKEN,
        pregunta:p,
        respuesta:o,
        observacion:'',
        modulo:m,
        submodulo:s,
        tipo:tipo, //1 es tipo de respuesta de opcion 2 es texto y 3 es ambos
    }
    console.log(form)
    axios.post('/api/enviarrespuesta_pregu', form).then(function (response) {
        if(response.status==200 || response.status==201){
            console.log(response.data)
            
        }else{
            Swal.fire({icon: 'error',text:'Algo salió mal'})
        }      
    })
    .catch(function (error) {
         Swal.fire({icon: 'error',text:error.response.data.message})
    }); 
}



function enviar_ambos_resultado(p,s,m){
    let form={
        _csrf:window.CSRF_TOKEN,
        pregunta:p,
        respuesta:resultado,
        observacion:observacion,
        modulo:m,
        submodulo:s,
        tipo:3, //1 es tipo de respuesta de opcion 2 es texto y 3 es ambos
    }
    console.log(form);
    axios.post('/api/enviarrespuesta_pregu', form).then(function (response) {
        if(response.status==200 || response.status==201){
            console.log(response.data)
           
        }else{
            Swal.fire({icon: 'error',text:'Algo salió mal'})
        }      
    })
    .catch(function (error) {
         Swal.fire({icon: 'error',text:error.response.data.message})
    }); 
}






function rellenar_form(p,o){
    
    b=0;
    if(form_respuesta){
        //evaluar si ya existe en el array 
        form_respuesta.forEach(pre=>{
            if(pre.pregunta==p){
                b++;
            }
        })
        if(b>0){
            //remplazar la respuesta   
            let indice=form_respuesta.findIndex( (element) => element.pregunta==p);
            form_respuesta.splice(indice, 1)
            form_respuesta.push({pregunta:p,respuesta:o});
        }else{
            form_respuesta.push({pregunta:p,respuesta:o});
        }
        
    }else{
        form_respuesta=[{pregunta:p,respuesta:o}];
    }
}   


function enviar_form(){
    return   
    if(form_respuesta){
        form_respuesta.sort(function (a, b){
            return (a.pregunta - b.pregunta)
        })
        console.table(
         form_respuesta
        )
        enviar_respuestas_conjunto(form_respuesta)
    }else{
        Swal.fire({icon: 'error',text:'Responda al menos una pregunta'})
    }
    
}

function enviar_respuestas_conjunto(request){
        let form={
            _csrf:window.CSRF_TOKEN,
            preguntas:request,
        }
        axios.post('/api/enviarrespuestas', form).then(function (response) {
        // window.location.href = '/inicio';
        Swal.fire({icon: 'success',text:response.data.message})
        console.log(response.data.preguntas)
        })
        .catch(function (error) {
             Swal.fire({icon: 'error',text:error.response.data.message})
        });   
}
