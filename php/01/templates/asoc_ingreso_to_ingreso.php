    <div class="row">

        <div class="span4">
            <div class="panel panel-success">
              <div class="panel-heading">Ingresos A</div>
              <div class="panel-body" style="height: 40em !important; ">

                <label for="dependenciasA" class="lblH2cmb">Lista de Dependencias </label>
                <select name="dependenciasA" id="dependenciasA" size="1" style="width:100% !important;" > 
                </select>

                <label for="grupo0" class="lblH2cmb">Lista de Conceptos de Ingresos A</label>
                    <select class="grupo0 " name="grupo0" id="grupo0" multiple="multiple" style="width:100% !important; height: 90% !important;" > 
                    </select>
                    <span class="label label-large label-yellow arrowed-right pull-left" id="lbl02"></span>
                    <div class="span3 pull-right">
                        <label>
                            <input name="chkAllGru" id="chkAllGru" class="ace ace-switch" type="checkbox">
                            <span class="lbl"></span>
                        </label>
                    </div>                    
               </div>
            </div>
        </div>

        <div class="span2">
            <div class="panel panel-default"  style="height: 43em;">
                <div class="panel-body" style="padding-top: 16em;">
                        <button id="AddItem" name="btnAsig" class="btnAsig btn btn-primary btn-lg wd100prc center" >
                            Asignar <span class="glyphicon glyphicon-chevron-right"></span></button><br/><br/>
                        <button id="DeleteItem" name="DeleteItem" class="btnDel btn btn-primary btn-lg wd100prc center" >
                            <span class="glyphicon glyphicon-chevron-left"></span>Quitar</button>
                </div>
            </div>
             <div class="div1em"></div>
        </div>

        <div class="span4">
            <div class="panel panel-primary">
              <div class="panel-heading">Ingresos B</div>
              <div class="panel-body" style="height: 40em !important; ">

                    <label for="dependenciasB" class="lblH2cmb">Lista de Dependencias </label>
                    <select name="dependenciasB" id="dependenciasB" size="1" style="width:100% !important;" > 
                    </select>

                    <label for="ingresosb" class="lblH2cmb">Lista de Conceptos de Ingresos B</label>
                    <select name="ingresosb" id="ingresosb" size="1" style="width:100% !important;" > 
                    </select>
                   
                    <label for="grupo1" class="lblH2">Conceptos de Ingresos Asociados</label>
                    <select class="grupo1" name="grupo1" id="grupo1" multiple="multiple" style="width:100% !important; height: 77% !important;" >
                    </select>
                    <span class="label label-large label-yellow arrowed pull-right" id="lbl01"></span>

               </div>
            </div>
        </div>

    </div>

    <div id="divAddNewGroup" class="modal fade " style = "height:400px !important;">
    </div><!-- /.modal -->

<script type="text/javascript">


function getDependencias(){
    var nc = "u="+localStorage.nc;
    $("#dependenciasA").html('');
    $("#dependenciasB").html('');
    $.post(obj.getValue(0)+"data/", { o:1, t:300, p:0,c:nc,from:0,cantidad:0, s:"" },
        function(json){
           $.each(json, function(i, item) {
                $("#dependenciasA").append('<option value="'+item.data+'"> '+item.label+'</option>');
                $("#dependenciasB").append('<option value="'+item.data+'"> '+item.label+'</option>');
            });

           getGpoDep( $("#grupo1") );   


            
        }, "json"
    );  
}

function getUsuarios(){
    var nc = "u="+localStorage.nc;
    $.post(obj.getValue(0)+"data/", { o:1, t:-1, p:10, c:nc, from:0, cantidad:0, s:"" },
        function(json){
           $.each(json, function(i, item) {
                $("#grupo0").append('<option value="'+item.iduser+'">'+item.apellidos+' '+item.nombres+' ('+item.username+') </option>');
            });
            $("#lbl02").html(commaSeparateNumber(json.length)+" usuario(s)")
            $("#preloaderPrincipal").hide();

            //getGpoDep( ciclo );

        }, "json"
    );  
}

// $("#ciclo1").on("change",function(event){
//     event.preventDefault();
//     getGpoDep( $("#grupo1") );
// });

function getGpoDepA(grupo){
    $("#preloaderPrincipal").show();
    var nc = "u="+localStorage.nc;
    grupo.html("");
    var y = $('select[name="dependenciasA"] option:selected').val(); 
    grupo.html("");
    $.post(obj.getValue(0)+"data/", {o:2, t:1, p:0,c:nc,from:0,cantidad:0, s:y },
        function(json){
           $.each(json, function(i, item) {
               grupo.append('<option value="'+item.data+'"> '+item.label+'</option>');
            });
            $("#lbl01").html(commaSeparateNumber(json.length)+" usuario(s)")
            $("#preloaderPrincipal").hide();
        }, "json"
    );  
}

function getGpoDepB(grupo){
    $("#preloaderPrincipal").show();
    var nc = "u="+localStorage.nc;
    grupo.html("");
    var y = $('select[name="dependenciasB"] option:selected').val(); 
    grupo.html("");
    $.post(obj.getValue(0)+"data/", {o:2, t:1, p:0,c:nc,from:0,cantidad:0, s:y },
        function(json){
           $.each(json, function(i, item) {
               grupo.append('<option value="'+item.data+'"> '+item.label+'</option>');
            });
            $("#lbl01").html(commaSeparateNumber(json.length)+" usuario(s)")
            $("#preloaderPrincipal").hide();
        }, "json"
    );  
}


function getGpoDepC(grupo){
    $("#preloaderPrincipal").show();
    var nc = "u="+localStorage.nc;
    grupo.html("");
    var y = $('select[name="ingresosb"] option:selected').val(); 
    grupo.html("");
    $.post(obj.getValue(0)+"data/", {o:2, t:2, p:0,c:nc,from:0,cantidad:0, s:y },
        function(json){
           $.each(json, function(i, item) {
               grupo.append('<option value="'+item.data+'"> '+item.label+'</option>');
            });
            $("#lbl01").html(commaSeparateNumber(json.length)+" usuario(s)")
            $("#preloaderPrincipal").hide();
        }, "json"
    );  
}

$("#chkAllGru").on("click",function(event){
    var checked =$(this).is(":checked")
    if (checked) {
        $("#grupo0 option").each(function(){
            $(this).prop('selected', true);
        });
    } else {
        $("#grupo0 option").each(function(){
            $(this).prop('selected', false);
        });
    }
});

$("#dependenciasA").on("change",function(event){
    event.preventDefault();
    getGpoDepA( $("#grupo0"));
});


$("#dependenciasB").on("change",function(event){
    event.preventDefault();
    getGpoDepB( $("#ingresosb") );
});

$("#ingresosb").on("change",function(event){
    event.preventDefault();
    getGpoDepC( $("#grupo1"));
});


$("#AddItem").on("click",function(event){
    $("#preloaderPrincipal").show();
    
    // Opciones asignadas a un determinado grupo
    var x = $('.grupo0 option:selected').val();  
    var y = $('select[name="ingresosb"] option:selected').val(); 

    if (isDefined(x)){
        $("#preloaderPrincipal").hide();
        alert("Seleccione una opción disponible");
        return false;
    }else{
        x='';
        $(".grupo0 option:selected").each(function () {
                x += $(this).val() + "|";
          });

    }
    if (isDefined(parseInt(y)) || y <= 0){
        $("#preloaderPrincipal").hide();
        alert("Seleccione un grupo");
        return false;
    }

    var d = x+'.'+y;
    
    var nc = "u="+localStorage.nc;

    $.post(obj.getValue(0)+"data/", { o:1, c:d, t:10, p:1, s:nc, from:0, cantidad:0 },
        function(json){
            //alert( json[0].msg);
            if (json.length<=0 && json[0].msg=="Error") { return false;}
                getGpoDepC( $("#grupo1") ); 
                $("#preloaderPrincipal").hide();
    }, "json");

});

$("#DeleteItem").on("click",function(event){
    $("#preloaderPrincipal").show();

    var x = $('.grupo1 option:selected').val();  
    
    if (isDefined(parseInt(x))){
        $("#preloaderPrincipal").hide();
        alert("Seleccione una opción disponible");
        return false;
    }else{
        x='';
        $(".grupo1 option:selected").each(function () {
                x += $(this).val() + "|";
          });

    }
    
    //alert(x);
    var nc = "u="+localStorage.nc;

    $.post(obj.getValue(0)+"data/", { o:1, c:x, t:20, p:1, s:nc, from:0, cantidad:0 },
        function(json){
            //alert(json[0].msg)
            if (json.length<=0 && json[0].msg=="Error") { return false;}
                //getDependencias();
                getGpoDepC( $("#grupo1") ); 
                $("#preloaderPrincipal").hide();
    }, "json");
});

getUsuarios( );    
getDependencias();

</script>



