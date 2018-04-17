(function ($) {
  $.fn.extend({
    FixedHead: function (options) {
      var op = $.extend({
        tableLayout: 'fixed',
        parentBox: '',          //父盒子，用于做选择器
        bottomBox: '',          //底部盒子，用户计算高度
        bgColor: 'transparent', //表头颜色
        adjustHeight: '21',     //调整高度
        minHeight: '200',       //表格最小高度
        autoHeight:false        //自动高度
      }, options);

      var isWebkit = (navigator.userAgent.indexOf('AppleWebKit') > -1) ? 10 : 17;
      //父盒子是否存在
      if($(op.parentBox).length === 0){
        op.parentBox = ''
      };
      if($.getBrowser()=="Edge"){
        isWebkit=12;
      }
      //console.log($(op.parentBox + '.parent_box').length);
      /*临时*/
      $(op.parentBox + '.parent_box').find('.w1800').css('width','1793px');
      $('.grid-view').css('overflow', 'hidden');
      //计算高度
      function winHeight(){
        var $bottomBox  = $(op.parentBox + op.bottomBox)
          , $boxHeight  = 0
          , $parent_box = $(op.parentBox + '.parent_box')
          , $bBoxLength = $bottomBox.length
          , newHeight   = 0
        ;

        if($parent_box.length === 1){
          newHeight   = parseInt($(window).height()) - parseInt($parent_box.offset().top) - op.adjustHeight
          //获取底部盒子高度
          if($bBoxLength > 0){
            $boxHeight = $bottomBox.height() + parseInt($bottomBox.css('padding-right')) + parseInt($bottomBox.css('padding-left'));
          };
          //console.info(newHeight, $boxHeight);
          if(op.autoHeight){
            var $table_tr = $(op.parentBox + '.parent_box tr')
              , trHeight  = 0
            ;
            $table_tr.each(function(){
              trHeight += $(this).height();
            });

            if(trHeight < op.minHeight){
              return trHeight - op.adjustHeight;
            }
            else{
              return op.minHeight;
            };

          }
          else{
            return (newHeight > op.minHeight ? newHeight : op.minHeight) - $boxHeight;
          };
        };

      };

      //计算表格高度
      function tableHeight() {
        //console.info("winHeight" + winHeight());
        var $parent_box = $(op.parentBox + '.parent_box')
          , $headWrap   = $(op.parentBox + '.headWrap')
        ;

        //判断top
        if($headWrap.width() !== $parent_box.width()){
          $(op.parentBox + '.headWrap').css("width", parseInt($(op.parentBox + '.tableWrap').width()) - isWebkit);
        };

        //设置top
        if($parent_box.position()){
          if(parseInt($headWrap.css('top')) !== $parent_box.position().top){
            $headWrap.css('top',$parent_box.position().top);
          };
        };

        //设置高度
        $parent_box.css({
           'height': winHeight() + 'px',
          //  'overflow': 'scroll'
          'overflow': 'auto'
        });

      };

      return this.each(function () {
        if($(this).parents('.tableWrap').length === 1) return;
        //console.info("内部");
        var $this = $(this).wrap('<div class="parent_box" style="position:relative;overflow:scroll"></div>') //指向当前的table
          , $thisParentDiv = $(this).parent() //指向当前table的父级DIV，这个DIV要自己手动加上去
        ;

        $thisParentDiv.wrap('<div class="tableWrap" style="overflow:hidden"></div>');

        tableHeight();

        var x = $(op.parentBox + '.parent_box').position();
        //$thisParentDiv.css("background-color","#f00");
        if(x){
          var fixedDiv = $('<div class="headWrap" style="clear:both;overflow:hidden;z-index:99;position:absolute;"></div>')
            .insertBefore($thisParentDiv)//在当前table的父级DIV的前面加一个DIV，此DIV用来包装tabelr的表头
            .css({'width': $(op.parentBox + '.tableWrap').clientWidth, 'left': x.left, 'top': x.top});
        }
        else{
          var fixedDiv = $('<div class="headWrap" style="clear:both;overflow:hidden;z-index:99;position:absolute;"></div>')
            .insertBefore($thisParentDiv)//在当前table的父级DIV的前面加一个DIV，此DIV用来包装tabelr的表头
            .css({'width': $(op.parentBox + '.tableWrap').clientWidth});
        }

        var $thisClone = $this.clone(true);
        $thisClone.removeAttr('id');
        $thisClone.html($thisClone.clone(true).find('tr:first'));
        $thisClone.appendTo(fixedDiv); //将表头添加到fixedDiv中
        // $this.find("thead").remove(); //删除原节点

        $this.css({'margin-top': 0, 'table-layout': op.tableLayout});
        //当前TABLE的父级DIV有水平滚动条，并水平滚动时，同时滚动包装thead的DIV
        $thisParentDiv.scroll(function () {
          fixedDiv[0].scrollLeft = $(this)[0].scrollLeft;
        });

        //因为固定后的表头与原来的表格分离开了，难免会有一些宽度问题
        //下面的代码是将原来表格中每一个TD的宽度赋给新的固定表头
        var $fixHeadTrs = $thisClone.find('tr:first').children()
          , $orginalHeadTrs = $this.find('tr:first')
          , HeadTrs
        ;

        $fixHeadTrs.each(function(indexTr){
          HeadTrs = $orginalHeadTrs.children('th:eq(' + indexTr + ')')
          $(this).css('width', HeadTrs.width() + HeadTrs.css('padding-right') + HeadTrs.css('padding-left'));
        });

        //设置背景颜色
        $(op.parentBox + '.headWrap').find('table').css('margin-bottom', '0px')
        .find('tr').css('background-color', op.bgColor);

        $(op.parentBox + '.parent_box').css('overflow', 'scroll');
        //监控宽度变化
        var tableWidth = 0
          , tableTop   = 0
        ;
         var changeHeight =function(){
            $.each($(".parent_box"),function(){
              var obj = $(this);
              var container =obj.closest(".tableWrap");
              if(obj.find("table").find("tbody").children("tr").length==0){
                  return false;
              }
              if(obj.height()>=obj.children().first().height()){
                 $(".headWrap",container).width(obj.width());
              }

              $(".headWrap",container).find("table").first().width(obj.find("table").first().width());  
              if($.getBrowser()!="IE10"){
                  $fixHeadTrs.each(function(indexTr){
                    HeadTrs = $orginalHeadTrs.children('th:eq(' + indexTr + ')')
                    $(this).width(HeadTrs.width());
                  });
              }
              

            }); 
         }
         changeHeight();
         $(window).resize(changeHeight);
        setInterval(function(){
       
 

          //监控
          if(!$(op.parentBox + '.tableWrap').length){
            //console.log('@@@@');
            $(op.parentBox + '.table.table-fixed').FixedHead({
              bgColor:op.bgColor,
              parentBox:op.parentBox,
              bottomBox:op.bottomBox,
              adjustHeight:op.adjustHeight,
              minHeight:op.minHeight
            });
            $(op.parentBox + '.headWrap').css('width', parseInt($(op.parentBox + '.tableWrap').width()) - isWebkit);
          };

          //监视高度改变
          if(tableTop !== winHeight()){
            //console.log('宽度修改了');
            tableHeight();
            tableTop = winHeight();
          };

          //监视宽度改变
          if(tableWidth !== $(op.parentBox + '.tableWrap').width() || $(op.parentBox + '.headWrap').width() === 0){
            //console.log($(op.parentBox + ".tableWrap").width());
            $(op.parentBox + '.headWrap').css('width', parseInt($(op.parentBox + '.tableWrap').width()) - isWebkit);
            tableWidth = $(op.parentBox + '.tableWrap').width();
          };
           changeHeight();
        }, 1);

      });
    }
  });

  //处理侧边栏切换清理缓存
  $(".fa-circle-o").parent("a").click(function(e){
        e.preventDefault();
        var target = $(this).attr('href');
        if(location.href.indexOf(target)==-1){
          var currentUrl = (location.href).split('?')[0];
          sessionStorage.removeItem(currentUrl);  //移除缓存
        }
        location.href = target;
  });
})(jQuery);

/**
 * 控制搜索面板
 * @param {Object} options
 */
function searchBox(options){
  var enableList =["orders","store-orders","orders-abnormal","orders-finance"];
  var currentUrl = (location.href).split('?')[0];
  var newSearchType = enableList.indexOf(options.type)!=-1;
  var op = $.extend({
        btn: '.content-header h1',   //事件按钮
        btnGroup:' .btnGroupBox',    //按钮组
        box: '.box.box-default',     //事件盒子
        show: sessionStorage.getItem(currentUrl) ||(newSearchType?"block":'none'), //是否显示搜索块
        btnName: '', //按钮名称
        type:'',
        searchField:[]
      }, options);
  // 判断是否是按按钮组
  if($(op.btn + op.btnGroup).length == 0){
    $(op.btn).append('<button type="button" class="btn btn-info" id="openSearch">' + (op.show === 'none' ? '打开搜索' : '收起搜索') + '</button>');
  }
  else{
    $(op.btn + op.btnGroup).prepend('<button type="button" class="btn btn-info" id="openSearch">' + (op.show === 'none' ? '打开搜索' : '收起搜索') + '</button>');
  };

  var $searchBox = $(op.box);
  var $searchTips = $("#searchTips") ;
  if(op.type==""){
    $searchBox.css('display',op.show);
    $('#openSearch').on('click', function(){
      if($searchBox.is(':hidden')){
        $(this).text('收起搜索');
        $searchBox.show();
        sessionStorage.setItem(currentUrl, 'block');

      }
      else{
        $(this).text('打开搜索');
         $searchBox.hide();
        sessionStorage.setItem(currentUrl, 'none');

      }
    });
  }
  //门店订单查询
  else if(newSearchType){
      searchToggle(op)
  }
  $(window).resize(function(){
       computeHiddenHeight($searchBox);
  });
  //外放接口
  return {
    "searchToggle":function () {
        searchToggle(op)
    }
  }
};


//搜索切换函数
function searchToggle(op){
  var currentUrl = (location.href).split('?')[0];
  var searchField =op.searchField;
   var $searchBox = $(op.box);
   var noData=false;
    if(location.href.indexOf('?')==-1&&location.href.indexOf("Orders/sub-orders-abnormal/index")==-1){
      $('#openSearch').hide();
       op.show="block";
    }
    if(sessionStorage.getItem("ALW_Search")!=null){
      op.show= "none";
      sessionStorage.removeItem("ALW_Search");
      sessionStorage.setItem(currentUrl, 'none');
    }

    //没有查询记录
    if($(".no-mb").children().length==0){
       $('#openSearch').hide();
       op.show= "block";
       noData = true;
    }
    if(op.show=="none"){
          $("#openSearch").text('打开搜索');
          $searchBox.css({
            'height':'0',
            'overflow':'hidden'
          })
    }else{
         $("#openSearch").text('收起搜索');
         $searchBox.css({
                        'transition':'none',
                        'height':'auto'
                        })
                    .attr('data-height', $searchBox.height())
    }

    //if($("#openSearch").data("events")&&!$("#openSearch").data("events")["click"]){
    $('#openSearch').unbind().bind('click', function(){

    $("body").css('overflow','hidden');
     this.disabled = true;
     this.loading=true;
     var isEmptySearch =$.trim($("#searchText").text())=="";
     //显示高级搜索
    if($searchBox.height()==0){
      computeHiddenHeight($searchBox);
      $(this).text('收起搜索');
      sessionStorage.setItem(currentUrl, 'block');
      var sb_height =parseInt($searchBox.attr('data-height'));
      $searchBox.css({
         'transition-delay':'0s',
         'transition-duration':".5s"
      }).height(sb_height);
      setTimeout(function(){
          $searchBox.css({
            'transition':'none',
            'height':'auto'
      });
      $("body").css('overflow-x','auto');
      }.bind(this),500);
      this.disabled = false;
    }
    //隐藏高级搜索
    else{
      var sh =$searchBox.height();
      $searchBox
      .height(sh)
      .attr('data-height', sh)
      .css({'transition':'height 0.3s 0s','overflow':'hidden'}); //开启过渡
      $searchBox.height(0);
      $(this).text('打开搜索');
      sessionStorage.setItem(currentUrl, 'none');
      this.disabled = false;
       $("body").css('overflow-x','auto');
    }
   });
    //}
    initAdvancSearch(!noData,searchField);

}


//计算隐藏内容
function computeHiddenHeight($wrap){
    if($wrap.height()==0){
         //打开元素
         $wrap.css({
           'transition':'none',
           'z-index':'-999',
           'position':'relative',
           'left':'-99999px',
           'height':'auto'
         })
         .attr('data-height',$wrap.height())  //计算高度
         .css({'position':'relative',
               'z-index':'auto',
               'height':'0',
                'left':'0',
               'transition':'height .3s 0s'
         });
    }
}


function initAdvancSearch(hasData,searchField){
    var txt =[];
    $.each(searchField,function(k,v){
    var label ='';
    var item = $("#"+v);
    var tmp='';
    if(item.children("option").length==0&&item.val()==''){
        return true;
    }else if(item.children("option").length==0&&item.val()!=''){
    tmp = $.trim(item.val());
    label =item.attr('placeholder');
    }else if(item.children("option").length>0
    &&item.find("option:checked").length>0
      &&item.val()!=''){
        label =item.find("option").first().text();
        tmp = $.trim(item.find("option:checked").text());
    }
      tmp!=''&& txt.push("<span style='margin-left:10px;'>"+label+"："+tmp+"；</span>");  //(k!=searchField.length-1?"；":"")
    });
    if(txt.length>0){
        $("#searchText").html("<span style=''>已筛选条件 ></span>"+txt.join(''));
    }else{
        $("#searchText").html("");
    }
    if(txt.length==0&&hasData){
      $("#searchText").html("<span>已筛选条件 ></span><span style='margin-left:10px;'>全部订单；</span>");
    }
}

/**
 * 滚动固定头部
 * @param {Object} options
 */
function pin_head(options){
  var op = $.extend({
        tabName: '',       //tab盒子名
        tableName: '',     //table盒子名
        other:'',          //其它盒子，这里预留tab下的按钮
        tableBtn:'',       //表格上的按钮
        tableWidth:'',     //表格宽度
        scrollBox:'',      //滚动的盒子
        pinBox:''          //pin插件选择器
      }, options);

  //创建盒子
  if(!document.getElementById('pinBox')){
    $(op.tabName)
    .wrap('<div id="pinBox" style="z-index:840;overflow:hidden;background-color: #fff;"></div>');
  };

  //是否添加按钮
  if(op.other){
    var other = $(op.other).clone(true);
    other.appendTo('#pinBox');
  };

  //创建表头
  var headWrap;
  if($('.headWrap').length === 0){
    headWrap = $('<div class="headWrap" style="clear:both;z-index:99;"></div>');
  }
  else{
    headWrap = $('.headWrap');
    headWrap.empty(); //清空节点类容，重置表格
  };

  //是否添加按钮
  if(op.tableBtn){
    var other = $(op.tableBtn).clone(true);
    other.appendTo(headWrap);
  };

  //组装表头
  var cloneTable = $(op.tableName).clone(true);
  cloneTable.removeAttr('id'); //删除table里的id
  cloneTable.html(cloneTable.clone(true).find('tr:first'));
  cloneTable.appendTo(headWrap);
  //设置table颜色和边距
  headWrap.find("table").css("margin-bottom", "0px").find("tr").css("background-color", "#fff");
  headWrap.appendTo('#pinBox');

  $("#pinBox").pin({
    containerSelector: op.pinBox,
    padding: {top: 50}
  });

  //覆盖固定盒子
  $(op.tableWidth).css({"position": "relative", "top": - headWrap.height()});

  //监听滚动条滚动
  if(op.scrollBox){
    $(op.scrollBox).scroll(function () {
      $("#pinBox").scrollLeft($(this).scrollLeft());
    });
  };

  //定时判断dom变化
  setInterval(function(){
    var headWrap      = $('#pinBox').width()         //获取固定盒子宽度
      , tabContent    = $(op.tableWidth).width()     //获取表格盒子宽度
      , slimScrollDiv = $('.slimScrollDiv').width()  //获取侧边菜单宽度
      , pinBox        = $(op.pinBox).width()
    ;

    //判断宽度是否改变
    if(document.getElementById('pinBox')){
      if($('#pinBox').css('position') == 'fixed'){
        $('#pinBox').css({'width': pinBox, 'left': slimScrollDiv})
        .find(op.tabName).css('width', 'auto');
      }
      else{
        $('#pinBox').css({'width': tabContent, 'left': slimScrollDiv})
        .find(op.tabName).css('width', tabContent);
      }
    };
  }, 1);
};

/**
 * 计算表高度
 * @param {Object} options
 */
function table_height(options){
  var op = $.extend({
        minusBox: []  //需要减去的盒子
      }, options);

    var boxNum    = (op.minusBox).length  //减去的盒子个数
      , boxHeight = 0
    ;
    for (var i=0; i<boxNum; i++){
      if($(op.minusBox[i]).length > 0){
        boxHeight += parseInt($(op.minusBox[i]).height())
          + parseInt($(op.minusBox[i]).css('padding-top'))
          + parseInt($(op.minusBox[i]).css('padding-bottom'))
          + parseInt($(op.minusBox[i]).css('margin-top'))
          + parseInt($(op.minusBox[i]).css('margin-bottom'))
        ;
        //console.info(boxHeight);
      };
    };
    //console.log(boxNum, boxHeight, $(window).height() - 50 - boxHeight);
    return $(window).height() - 50 - boxHeight;
};
