/**
 * imgLazyLoad v1.0.0 - jQuery plugin for lazy loading images
 * https://github.com/Barrior/imgLazyLoad
 * Copyright 2016 Barrior <Barrior@qq.com>
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 */
(function( $, win ){

    'use strict';

    $.fn.imgLazyLoad = function( options ){

        var elements = this,
            settings = $.extend({
                container: win,
                effect: 'fadeIn',
                speed: 600,
                delay: 400,
                callback: function(){}
            }, options ),

            container = $( settings.container ),

            loading = function(){

                //当所有的图片都加载完，移除滚动事件
                if( !elements.length ){
                    return container.off( 'scroll.lazyLoad' );
                }

                var containerHeight = container.outerHeight(),
                    containerTop = container.scrollTop();

                if( settings.container !== win ){
                    containerTop = container.offset().top;
                }

                elements.each(function(){

                    var $this = $( this ),
                        top = $this.offset().top;

                    if( containerTop + containerHeight > top &&
                        top + $this.outerHeight() > containerTop ){

                        // Caso o elemento nao esteja na tela (tabs)
                        if(! $this.is(':visible')) return;

                        //删除jQuery选择好的元素集合中已经被加载的图片元素
                        elements = elements.not( $this );

                        var loadingSrc = $this.attr( 'data-src' );

                        var img = new Image();
                        img.src = loadingSrc;
                        img.onload = function () {
                            if($this[0].tagName === "IMG") {
                                $this.attr('src', loadingSrc).removeAttr( 'data-src').addClass('loaded');
                            } else {
                                $this.css('background-image', "url('" + loadingSrc + "')" ).removeAttr( 'data-src').addClass('loaded');
                            }
                        };
                        img.onerror = function() {
                            $this.removeAttr( 'data-src' ).addClass('not-loaded');
                        };

                        //$( new Image() ).prop( 'src', loadingSrc ).load(function(response, status, xhr){
                        //    console.log(response);
                        //    console.log(status);
                        //    console.log(xhr);
                        //    //替换图片路径并执行特效
                        //    $this.hide().css('background-image', "url('" + loadingSrc + "')" )
                        //        [ settings.effect ]( settings.speed, function(){
                        //        settings.callback.call( this );
                        //    }).removeAttr( 'data-src' );
                        //
                        //});

                    }

                });
            },

            throttle = function( fn, delay ){

                if( !delay ){
                    return fn;
                }

                var timer;

                return function(){

                    clearTimeout( timer );

                    timer = setTimeout(function(){

                        fn();

                    }, delay );

                }

            };

        if( !container.length ){

            throw settings.container + ' is not defined';

        }

        //开始便加载已经出现在可视区的图片
        loading();

        //滚动监听，显示图片
        container.on( 'scroll.imgLazyLoad', throttle( loading, settings.delay ) );

        // Change tab
        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', () => {
            loading();
        });

        return this;
    };

})( jQuery, window );