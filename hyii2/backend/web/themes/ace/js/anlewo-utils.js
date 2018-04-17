(function(){

     /**
     生成guid
     */
     $.Guid = function (){
        var guid = "";
        for (var i = 1; i <= 32; i++){
        var n = Math.floor(Math.random()*16.0).toString(16);
        guid +=   n;
        if((i==8)||(i==12)||(i==16)||(i==20))
            guid += "-";
        }
        return guid;
    }


    $.getBrowser=function(){
        if(!!window.ActiveXObject || "ActiveXObject" in window){
          return "IE";
        }
        if(navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.match(/7./i)=="7.")
        {
            return "IE7";
        }
        else if(navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.match(/8./i)=="8.")
        {
            return "IE8";
        }
        else if(navigator.appName == "Microsoft Internet Explorer" && navigator.appVersion.match(/9./i)=="9.")
        {
            return "IE9";
        }
        else if(navigator.appName == "Microsoft Internet Explorer")
        {
            return "IE10";
        }else if(navigator.userAgent.lastIndexOf("Edge") > -1){
            return "Edge";
        }
    }

   /**
   * 显示加载层
   * @param {picClassName} 图片样式
   * @param {desc}         文字展示
   * @return modal         弹层对象
   */
    $.ALW_Loading = function(picClassName,desc){
        var load_id =$.Guid()+picClassName;
        var modal = $("#"+load_id);
        if(modal.length==0){
            modal =  $('<div class="modal fade in search-modal" style="display:none;" id="'+load_id+'">'+
            '<div class="alw-loading">'+
                '<div class="'+picClassName+'"></div>'+
                '<div class="alw-loading-text">'+desc+'</div>'+
            '</div>'+
            '</div>');
            modal.appendTo("body");
        }
        return {
            "show":function(){
                if($.getBrowser()=="IE"){
                  if("alw-loading-pic"==picClassName){
                    $(".alw-loading-pic",modal).removeClass('alw-loading-pic');
                  }
                    setTimeout(function(){
                        modal.find("."+picClassName).toggleClass('alw-loading-pic-animate');
                        setTimeout(arguments.callee,200);
                    }.bind(this),200);
                }
                modal.show();
            },
            "hide":function(){
                modal.hide();
            },
            "remove":function(){
                 modal.remove();
            }
        }
    }


   /**
   * 判断当前浏览器是否支持css3属性
   *
   * @param {attr}         css属性
   * @return true,false    是否支持该属性
   */
    $.supportCssProperty=function( attr ){
            var prefix = [ 'O', 'ms', 'Moz', 'Webkit' ],
                length = prefix.length,
                style = document.createElement( 'i' ).style;
            cssProperty =  function( attr ){
                if( attr in style ){
                    return true;
                }
                attr = attr.replace( /^[a-z]/, function( val ){
                    return val.toUpperCase();
                });
                var len = length;
                while( len-- ){
                    if( prefix[ len ] + attr in style ){
                       return true;
                    }
                }
                return false;
            };
            return cssProperty( attr );
        }


     /**
   * 显示加载层
   * @param {btn}          按钮
   * @param {opts}         参数
   * @return modal         弹层对象
   */
    $.ALW_Logger =function(btn,opts){
          var opts = $.extend({"selector":$("body"),contentSelector:".log-content","wrapSelector":".log-modal"},opts);
          var caculateWrap = $(opts.contentSelector,wrap);
          var wrap = opts.selector;
          var content =wrap.children().first();
          var modal = wrap.find(opts.wrapSelector).first();
          var closeFun = function(){
                wrap.closest("tr").removeClass('tr-log-active');
                content.animate({right: '-600px'}, "500",function(){
                    wrap.hide();
                    btn.locked = false;
                })
                return false;
               if($.supportCssProperty("transition")){
                    content.css('right',"-600px");
                    setTimeout(function(){
                        wrap.hide();
                    },800);
                }else{
                    content.animate({right: '-600px'}, "slow",function(){
                        wrap.hide();
                    })
                }

          }
          content.css({'right':'-600px','position':'absolute'}).click(function(){return false;});
          caculateWrap.height(modal.height()-38);
          wrap.click(closeFun);
          $(window).resize(function(){
             caculateWrap.height(modal.height()-38);
          });

         //收缩按钮
         $(".double-icon",wrap).on("click", function() {
                var btn = $(this);
                var li = btn.closest("li");
                if($(this).parent(".timeline-header").next(".timeline-body").is(":hidden")) {
                    //处理其他记录
                    li.siblings("li").find(".timeline-body").hide();
                    li.siblings("li").find(".log-icon").removeClass("log-icon-active");
                    li.siblings("li").find(".double-icon").addClass("fa-angle-double-down").removeClass("fa-angle-double-up")

                    li.find(".log-icon").addClass("log-icon-active");
                    li.find(".timeline-body").show();
                    btn.removeClass("fa-angle-double-down").addClass("fa-angle-double-up");
                }else {
                    li.find(".timeline-body").hide();
                    btn.removeClass("fa-angle-double-up").addClass("fa-angle-double-down");
                    li.find(".log-icon").removeClass("log-icon-active");
                }
                return false;
            });


          return {
               show:function(){
                   wrap.closest("tr").addClass("tr-log-active");
                   //var h = wrap.closest(".content-wrapper").height()-50;
                   wrap.show(function(){
                       caculateWrap.height(modal.height()-38);
                       content.animate({right: '0'}, "500");
                       return false;
                       if($.supportCssProperty("transition")){
                           content.css('right',"0")
                       }else{
                           console.log("IE9 打开")
                           content.animate({right: '0'}, "slow")
                       }

                   });
               },
               hide:function(){
                  closeFun();
               },
               remove:function(){
                    item.remove();
               },
               update:function(){

               }
           }
    }

   /**
   *
   * 绑定侧边栏回调事件
   * @param {collapsed}    收起完毕回调函数
   * @param {expanded}     展开完毕回调函数
   * @return undefined
   * $.BindPushMenu(function(){alert($(".main-sidebar").width())},function(){alert($(".main-sidebar").width())})
   */
    $.BindPushMenu =function(collapsed,expanded){
         $("body").bind('collapsed.pushMenu',function(){setTimeout(collapsed,300)});
         $("body").bind('expanded.pushMenu',function(){setTimeout(expanded,300)});
    }

    /**
    *
    * 解除侧边栏回调事件
    * @param {unBindCollapsed}    解除收起回调函数
    * @param {unBindExpanded}     解除展开回调函数
    *
    */
    $.UnBindPushMenu  = function(unBindCollapsed,unBindExpanded){
        if(!!unBindCollapsed){
             $("body").unbind('collapsed.pushMenu');
        }
        if(!!unBindExpanded){
             $("body").unbind('expanded.pushMenu');
        }
    }

    /**
    *
    * 安乐窝AJAX
    * @param {opts}    AJAX参数
    *
    */
    $.ALW_AJAX=function(opts){
        console.log("ALW_AJAX.....");
        var hideImg = opts.hideImg||false
        ,loadDesc = opts.loadDesc||"努力加载中<alw>...</alw>"
        ,loadObj =$.ALW_Loading(!hideImg?"alw-loading-pic":"",loadDesc);
        opts = $.extend(true,{
            beforeSend:loadObj.show,
            complete:loadObj.hide,
            error:function(){
                ANLEWO.alert('>_<, 服务器受到一个爆击导致失败，请稍后再试~ ');
            },
            success:function(){}
        },opts);
        return $.ajax({
            url:opts.url,
            data:opts.data,
            type:opts.type||"GET",
            beforeSend:opts.beforeSend
        })
        .success(opts.success)
        .fail(opts.error)
        .always(opts.complete)
    }

    /**
    *
    * 安乐窝Get请求
    * @param {url}    URL
    * @param {successFunc}    成功执行函数
    */
    $.ALW_GET=function(url,successFunc){
        console.log("ALW_GET.....");
        return  $.ALW_AJAX({
            url:url,
            success:successFunc,
            type:"GET"
        })
    }

    /**
    *
    * 安乐窝POST请求
    * @param {url}    URL
    * @param {data}   参数数据
    * @param {successFunc}    成功执行函数
    */
    $.ALW_POST=function(url,data,successFunc){
        console.log("ALW_POST.....");
        return  $.ALW_AJAX({
            url:url,
            success:successFunc,
            type:"POST",
            data:data
        })
    }

    /**
    *
    * 安乐窝数据加载层
    * @return 层对象
    */
    $.ALW_Data_Loading = function(){
       return  $.ALW_Loading("alw-loading-pic","努力加载中<alw>...</alw>");
    }

    /**
    *
    * 安乐窝重置加载层
    * @return 层对象
    */
    $.ALW_Reset_Loading = function(){
       return  $.ALW_Loading("","重置中<alw>...</alw>");
    }

    $.fn.extend({
        /**
        *
        * 安乐窝PAJAX前端请求
        * @opts 参数对象
        * {
        *   customModal:false   是否启用自定义弹层
        *   beforeSend:false    发送前回调
        *   error:false         错误回调
        *   success:false       成功回调
        *   complete:false      完成回调
        * }
        */
        ALW_PAJAX : function(opts){
            return this.each(function(){
                opts =opts||{};
                var loadObj= $.ALW_Data_Loading();
                var container = $(this);
                var customModal =opts.customModal||false;
                container.on('pjax:beforeSend',function(args){
                    !customModal&&loadObj.show();
                    opts.beforeSend&&opts.beforeSend();
                })
                .on('pjax:error',function(args){
                    opts.error&&opts.error();
                })
                .on('pjax:success',function(args){
                    opts.success&&opts.success();
                })
                .on('pjax:complete',function(args){
                    !customModal&&loadObj.hide();
                    opts.complete&&opts.complete();
                })
            });
        }
    });
})($);