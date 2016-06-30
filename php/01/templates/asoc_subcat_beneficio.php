    <div class="row">

        <div class="span4">
            <div class="panel panel-success">
              <div class="panel-heading">Servicios</div>
              <div class="panel-body" style="height: 33em !important; ">

                    <label for="grupo0" class="lblH2cmb">Servicios Activos </label>
                    <select class="grupo0 " name="grupo0" id="grupo0" multiple="multiple" style="width:100% !important; height: 87% !important;" > 
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
            <div class="panel panel-default"  style="height: 36em;">
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
              <div class="panel-heading">Bloques</div>
              <div class="panel-body" style="height: 33em !important; ">

                    <label for="beneficios" class="lblH2cmb">Lista de Bloques</label>
                    <select name="beneficios" id="beneficios" size="1" style="width:100% !important;" > 
                    </select>
                   
                    <label for="grupo1" class="lblH2">Servicios Asignados:</label>
                    <select class="grupo1" name="grupo1" id="grupo1" multiple="multiple" style="width:100% !important; height: 70% !important;" >
                    </select>
                    <span class="label label-large label-yellow arrowed pull-right" id="lbl01"></span>

               </div>
            </div>
        </div>

    </div>

    <div id="divAddNewGroup" class="modal fade " style = "height:400px !important;">
    </div>
<script type="text/javascript">


function getBeneficios(){
    var nc = "u="+localStorage.nc;
    $("#beneficios").empty();
    $.post(obj.getValue(0)+"data/", { o:1, t:300, p:0,c:nc,from:0,cantidad:0, s:"" },
        function(json){
           $.each(json, function(i, item) {
                $("#beneficios").append('<option value="'+item.data+'"> '+item.label+'</option>');
            });

           getGpoDep( $("#grupo1") );   
            
        }, "json"
    );  
}

function getSubcategorias(){
    var nc = "u="+localStorage.nc;
    $.post(obj.getValue(0)+"data/", { o:1, t:1, p:10, c:nc, from:0, cantidad:0, s:"" },
        function(json){
           $.each(json, function(i, item) {
                $("#grupo0").append('<option value="'+item.idsubcategoria+'">'+item.subcategoria+'</option>');
            });
            $("#lbl02").html(commaSeparateNumber(json.length)+" elementos(s)")
            $("#preloaderPrincipal").hide();

            //getGpoDep( ciclo );

        }, "json"
    );  
}

$("#ciclo1").on("change",function(event){
    event.preventDefault();
    getGpoDep( $("#grupo1") );
});


function getGpoDep(grupo){
    $("#preloaderPrincipal").show();
    var nc = "u="+localStorage.nc;
    var y = $('select[name="beneficios"] option:selected').val(); 
    grupo.empty();
    $.post(obj.getValue(0)+"data/", {o:2, t:3, p:0,c:nc,from:0,cantidad:0, s:y },
        function(json){
           $.each(json, function(i, item) {
               grupo.append('<option value="'+item.data+'"> '+item.label+'</option>');
            });
            $("#lbl01").html(commaSeparateNumber(json.length)+" elemento(s)")
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

$("#beneficios").on("change",function(event){
    event.preventDefault();
    getGpoDep( $("#grupo1"), $("#ciclo1") );
});


$("#AddItem").on("click",function(event){
    event.preventDefault();
    $(this).prop('disabled', true);
    $("#preloaderPrincipal").show();
    
    // Opciones asignadas a un determinado grupo
    var x = $('.grupo0 option:selected').val();  
    var y = $('select[name="beneficios"] option:selected').val(); 

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
    
    //alert(d);

    var nc = "u="+localStorage.nc;

    $.post(obj.getValue(0)+"data/", { o:2, c:d, t:10, p:1, s:nc, from:0, cantidad:0 },
        function(json){
            //alert( json[0].msg);
            if (json.length<=0 && json[0].msg=="Error") { return false;}
                getGpoDep( $("#grupo1") ); 
                $("#preloaderPrincipal").hide();
                $("#AddItem").prop('disabled', false);

    }, "json");
});

$("#DeleteItem").on("click",function(event){
    event.preventDefault();
    $(this).prop('disabled', true);
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

    $.post(obj.getValue(0)+"data/", { o:2, c:x, t:20, p:1, s:nc, from:0, cantidad:0 },
        function(json){
            //alert(json[0].msg)
            if (json.length<=0 && json[0].msg=="Error") { return false;}
                //getBeneficios();
                getGpoDep( $("#grupo1") ); 
                $("#preloaderPrincipal").hide();
                $("#DeleteItem").prop('disabled', false);
    }, "json");
});

getSubcategorias( );    
getBeneficios();

</script>



