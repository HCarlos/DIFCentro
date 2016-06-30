// JavaScript Document


$(document).on("ready", init);

function init() {

    
    obj.setIsTimeLine(true);

    if (!sessionStorage.Id) {
        if ($("#username").length <= 0) {
            window.location.href = obj.getValue(0);
        }
    }

    var IDTUSer = parseInt(localStorage.IdUserNivelAcceso, 0);

    $("#preloaderPrincipal").hide();

    uParam0 = obj.getkeyUP(IDTUSer, 1);
    if (uParam0 !== -1) {
        $.post(obj.getValue(0) + "menu-opciones/", {},
            function(html) {
                $("#menuPrincipal").append(html);
            }, "html");
    }

    uParam0 = obj.getkeyUP(IDTUSer, 0);
    if (uParam0 !== -1) {
        $.post(obj.getValue(0) + "menu-catalogos/", {},
            function(html) {
                $("#menuPrincipal").append(html);
            }, "html");
    }

    uParam0 = obj.getkeyUP(IDTUSer, 0);
    if (uParam0 !== -1) {
        $.post(obj.getValue(0) + "menu-config/", {},
            function(html) {
                $("#menuPrincipal").append(html);
            }, "html");
    }

    window.onload = function() {

        $("#preloaderSingle").hide();

        if (!sessionStorage.name) {
            if ($("#username").length <= 0) {
                window.location.href = obj.getValue(0);
            }
        }
        if ($("#menuOptsMain").length) {
            $.post(obj.getValue(0) + "session/", {},
                function(html) {
                    $("#menuOptsMain").prepend(html);
                    $.post(obj.getValue(0) + "data/", {
                            o: 1,
                            t: -3,
                            p: 11,
                            c: "u=" + localStorage.nc,
                            from: 0,
                            cantidad: 0,
                            s: ""
                        },
                        function(json) {
                            obj.setConfig($.map(json, function(el) {
                                return el;
                            }));
                        }, "json"
                    );
                }, "html");
        }

        $("#contentMain").html("");
        $("#contentProfile").html("");
        $("#contentLevel3").html("");

        var cantidad = 5;
        var cvsEl, ctx;
        if (!window.WebGLRenderingContext) {

            //window.location = "http://get.webgl.org";

        } else {

            var canvas = document.createElement('canvas'),
                context;

            if (!canvas.getContext) {
                $("#alertaNavegador").removeClass("hide");
                $("#signin-row").addClass("hide");
            } else {
                $("#signin-row").removeClass("hide");
                $("#alertaNavegador").addClass("hide");
            }
        }

        resizeScreen();


    }; // End Window.onload

}

function resizeScreen() {
    var hH;

    if ($("#menuOptsMain").length) {
        if (!obj.getFormResponse()) {
            $("#contentMain").html($("#InitDiv").html());
            hH = $( document ).height() - 130;
            obj.setMinHeight(hH);
            
            $("#contentMain").css("min-height", obj.getMinHeight());
            $("#contentProfile").css("min-height", obj.getMinHeight());
            $("#contentLevel3").css("min-height", obj.getMinHeight());
            obj.setFormResponse(true);

            //alert("Resize");
        } else {
            $("#contentMain").css("min-height", obj.getMinHeight());
        }
        //alert(obj.getMinHeight());
    }

    if ($("#signin-row").length) {
        if (!obj.getFormResponse()) {
            hH = $(this).height() - ($(".footer").height() * 10.0);
            $("#signin-row").css("min-height", hH);
            obj.setFormResponse(true);
        }
    }

}

function resizeScreenProfile() {
    var hH;

    $("#contentProfile").html($("#InitDiv").html());
    hH = $( document ).height() - 130;
    obj.setMinHeight(hH);
    $("#contentProfile").css("min-height", obj.getMinHeight());
    obj.setFormResponse(true);

}
