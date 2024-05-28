//IntellyWP
jQuery('.wrap .updated.fade').remove();
jQuery('.woocommerce-message').remove();
jQuery('.error').remove();
jQuery('.info').remove();
jQuery('.update-nag').remove();

jQuery(function() {
    "use strict";
    //WooCommerce errors
    var removeWooUpdateTheme = setInterval(function () {
        if (jQuery('.wrap .updated.fade').length > 0) {
            jQuery('.wrap .updated.fade').remove();
            clearInterval(removeWooUpdateTheme);
        }
    }, 100);
    var removeWooMessage = setInterval(function () {
        if (jQuery('.woocommerce-message').length > 0) {
            jQuery('.woocommerce-message').remove();
            clearInterval(removeWooMessage);
        }
    }, 100);

    jQuery('.wrap .updated.fade').remove();
    jQuery('.woocommerce-message').remove();
    jQuery('.error').remove();
    jQuery('.info').remove();
    jQuery('.update-nag').remove();
});

jQuery(function() {
    if(jQuery('.wrap .updated.fade').length>0) {
        jQuery('.wrap .updated.fade').remove();
    }
    if(jQuery('.woocommerce-message').length>0) {
        jQuery('.woocommerce-message').remove();
    }
    jQuery('.update-nag,.updated,.error').each(function() {
        var $self=jQuery(this);
        if(!$self.hasClass('iwp')) {
            $self.remove();
        }
    });
});

jQuery(function() {
    "use strict";

    //WooCommerce errors
    var removeWooUpdateTheme=setInterval(function() {
        if(jQuery('.wrap .updated.fade').length>0) {
            jQuery('.wrap .updated.fade').remove();
            clearInterval(removeWooUpdateTheme);
        }
    }, 100);
    var removeWooMessage=setInterval(function() {
        if(jQuery('.woocommerce-message').length>0) {
            jQuery('.woocommerce-message').remove();
            clearInterval(removeWooMessage);
        }
    }, 100);

    jQuery('form').on('submit', function() {
        jQuery('input, select').prop('disabled', false);
        return true;
    });

    var LB_visibleChanges={};
    jQuery('input,select,textarea').each(function() {
        var $self=jQuery(this);
        var name=ICP.attr($self, 'name', '');
        var visible=ICP.attr($self, 'ui-visible', '');
        if(visible!='') {
            var conditions=visible.split('&');
            var index=0;
            for(index=0; index<conditions.length; index++) {
                var k=conditions[index].split(':');
                k=k[0];
                var v=LB_visibleChanges[k];
                if(v==undefined) {
                    v=new Array();
                }
                v.push(name);
                LB_visibleChanges[k]=v;
            }
        }
    });

    jQuery.each(LB_visibleChanges, function(k,v) {
        var $what=jQuery('[name='+k+']');
        $what.change(function() {
            jQuery.each(v, function(i,name) {
                var $self=jQuery('[name='+name+']');
                var $div=jQuery('#'+name+'-row');
                var visible=ICP.attr($self, 'ui-visible', '');

                var all=true;
                var conditions=visible.split('&');
                var index=0;
                for(index=0; index<conditions.length; index++) {
                    var text=conditions[index].split(':');
                    var $compare=ICP.jQuery(text[0]);
                    var current=ICP.val($compare);
                    var options=text[1];
                    options=options.split('|');

                    var found=false;
                    jQuery.each(options, function(i,compare) {
                        if(compare!='' && compare==current) {
                            found=true;
                            return false;
                        }
                    });

                    if(!found) {
                        all=false;
                        break;
                    }
                }

                if(all) {
                    $div.show();
                } else {
                    $div.hide();
                }
            });
        });
        $what.trigger('change');
        //console.log('WHAT=%s TRIGGER CHANGE', ICP.attr($what, 'name'));
    });

    if(jQuery().multiselect) {
        jQuery('.icp-multiselect').multiselect({
            buttonClass: 'btn btn-default btn-sm ph15',
            dropRight: true
        });
    }
    jQuery('.icp-dropdown').each(function() {
        var $self=jQuery(this);
        var options={};
        ICP.select2($self, options);
        ICP.changeShowOptions($self);

        var ajax=ICP.attr($self, 'icp-ajax', false);
        var lazy=ICP.attr($self, 'icp-lazy', false);
        var help=ICP.attr($self, 'icp-help', '');
        var parent=ICP.attr($self, 'icp-master', '');

        if (parent!=='') {
            var masters=parent.split('|');
            var $parent=false;
            if(masters.length==1) {
                $parent=ICP.jQuery(masters[0]);
            } else {
                //register only to the last
                $parent=ICP.jQuery(masters[masters.length-1]);
            }
            $parent.change(function() {
                //console.log('PARENT CHANGE %s > %s'
                //    , ICP.attr($parent, 'name'), ICP.attr($self, 'name'));
                ICP.select2($self, {data: []});

                var parentId=ICP.val(parent);
                var check=false;
                if(parentId!==undefined && parentId!='') {
                    var array=parentId.split('|');
                    check=true;
                    jQuery.each(array, function(i,v) {
                        if(v=='') {
                            check=false;
                            return false;
                        }
                    });
                }
                if(lazy && parentId!='' && check) {
                    $parent.prop('disabled', true);
                    $self.prop('disabled', true);

                    var id=$self.attr('id');
                    var $text=jQuery('#select2-'+id+'-container .select2-selection__placeholder');
                    var placeholder=$text.html();
                    $text.html('Loading data..');

                    jQuery.ajax({
                        type: 'POST'
                        , dataType: 'json'
                        , data: {
                            //action: 'lb_ajax_ll'
                            //, lb_action: lazy
                            action: lazy
                            , parentId: parentId
                        }
                        , success: function(data) {
                            //console.log('success');
                            //console.log(data);

                            ICP.select2($self, {data: data});
                            $self.prop('disabled', false);
                            $parent.prop('disabled', false);
                            $text.html(placeholder);
                        }
                        , error: function(data) {
                            //console.log('error');
                            //console.log(data);

                            $self.prop('disabled', false);
                            $parent.prop('disabled', false);
                            $text.html(placeholder);
                        }
                    });
                }
            });
            /*var v=$self.val();
            if(v==null || (jQuery.isArray(v) && v.length==0) || v=='') {
                $parent.trigger('change');
            }*/
        }
    });
    jQuery('.icp-tags').each(function() {
        var $self=jQuery(this);
        var options={
            tags: true
            , tokenSeparators: [',', ' ']
        };
        ICP.select2($self, options);
        ICP.changeShowOptions($self);
    });

    jQuery('.icp-tags, .icp-dropdown').change(function() {
        var $self=jQuery(this);
        ICP.changeShowOptions($self);
    });

    jQuery(".icp-hideShow").click(function () {
        ICP.hideShow(this);
    });
    jQuery(".icp-hideShow").each(function () {
        ICP.hideShow(this);
    });
    jQuery(".alert-dismissable .close").on('click', function() {
        var $self=jQuery(this);
        $self.parent().parent().remove();
    });

    if(jQuery.colorpicker) {
        jQuery('.icp-colorpicker').colorpicker();
    }

    //icp-timer
    jQuery('.icp-timer').on('change', function() {
        var $self=jQuery(this);
        var name=ICP.attr($self, 'name');

        var $days=ICP.jQuery(name+'Days');
        var $hours=ICP.jQuery(name+'Hours');
        var $minutes=ICP.jQuery(name+'Minutes');
        var $seconds=ICP.jQuery(name+'Seconds');

        var text=$days.val()+':'+$hours.val()+':'+$minutes.val()+':'+$seconds.val();
        text=ICP.formatTimer(text);
        $self.val(text);

        text=text.split(':');
        $days.val(parseInt(text[0]));
        $hours.val(parseInt(text[1]));
        $minutes.val(parseInt(text[2]));
        $seconds.val(parseInt(text[3]));
    });
    jQuery('.icp-timer').each(function() {
        var $self=jQuery(this);
        var name=ICP.attr($self, 'name');

        var $days=ICP.jQuery(name+'Days');
        var $hours=ICP.jQuery(name+'Hours');
        var $minutes=ICP.jQuery(name+'Minutes');
        var $seconds=ICP.jQuery(name+'Seconds');

        $days.on('change', function() {
            $self.trigger('change');
        })
        $hours.on('change', function() {
            $self.trigger('change');
        })
        $minutes.on('change', function() {
            $self.trigger('change');
        })
        $seconds.on('change', function() {
            $self.trigger('change');
        })
        $self.trigger('change');
    });

    /*jQuery('.icp-time:not([readonly])').timepicker({
        beforeShow: function(input, inst) {
            var themeClass='theme-primary';
            inst.dpDiv.wrap('<div class="'+themeClass+'"></div>');
        }
    });*/
    jQuery('.icp-datetime:not([readonly])').datetimepicker({
        prevText: '<i class="fa fa-chevron-left"></i>'
        , nextText: '<i class="fa fa-chevron-right"></i>'
        , format: 'DD/MM/YYYY HH:mm'
        , beforeShow: function(input, inst) {
            var themeClass='theme-primary';
            inst.dpDiv.wrap('<div class="'+themeClass+'"></div>');
        }
        , firstDay: 1
    });
    if(jQuery(".icp-date:not([readonly])").length>0) {
        jQuery(".icp-date:not([readonly])").datepicker({
            prevText: '<i class="fa fa-chevron-left"></i>'
            , nextText: '<i class="fa fa-chevron-right"></i>'
            , showButtonPanel: false
            , dateFormat: 'dd/mm/yy'
            , beforeShow: function(input, inst) {
                var themeClass='theme-primary';
                inst.dpDiv.wrap('<div class="'+themeClass+'"></div>');
            }
            , firstDay: 1
        });
    }

    /*if(jQuery(".ecTags").length>0) {
        jQuery(".ecTags").select2({
            placeholder: "Type here..."
            , theme: "classic"
            , width: '300px'
        });
    }

    if(jQuery(".ecColorSelect").length>0) {
        jQuery(".ecColorSelect").select2({
            placeholder: "Type here..."
            , theme: "classic"
            , width: '300px'
            , formatResult: ICP_formatColorOption
            , formatSelection: ICP_formatColorOption
            , escapeMarkup: function(m) {
                return m;
            }
        });
    }*/
    jQuery('.icp-button-toggle').on('click', function() {
        var $self=jQuery(this);
        var showClass=$self.attr('data-filter');
        if(showClass=='') {
            return;
        }
        var pos=showClass.lastIndexOf('-');
        var baseClass=showClass.substring(0, pos);

        //console.log('baseClass=%s, count=%s', showClass, jQuery('.'+baseClass).length);
        //console.log('showClass=%s, count=%s', showClass, jQuery('.'+showClass).length);

        $self.parent().children().each(function(i,v) {
            var $this=jQuery(this);
            if(!$this.hasClass('icp-button-toggle')) {
                return;
            }

            var thisClass=$this.attr('data-filter');
            if(thisClass.indexOf(baseClass)===0) {
                $this.removeClass('active');
                $this.removeClass('btn-info');
            }
        });

        jQuery('.'+baseClass).hide();
        jQuery('.'+showClass).show();
        $self.addClass('active');
        $self.addClass('btn-info');
    });

    jQuery.browser = {};
    (function () {
    jQuery.browser.msie = false;
    jQuery.browser.version = 0;
    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
    jQuery.browser.msie = true;
    jQuery.browser.version = RegExp.$1;
    }
    })();

    if(jQuery('[data-toggle=tooltip]').qtip) {
        jQuery('[data-toggle=tooltip]').qtip({
            position: {
                corner: {
                    target: 'topMiddle',
                    tooltip: 'bottomLeft'
                }
            },
            show: {
                when: {event: 'mouseover'}
            },
            hide: {
                fixed: true,
                when: {event: 'mouseout'}
            },
            style: {
                tip: 'bottomLeft',
                name: 'blue'
            }
        });
    }
    
});

jQuery(function() {
    var href = jQuery("#icpRedirect").attr("href") || [];
    if (href.length > 0) {
        window.location.replace(href);
    }
 });

 !function(d,s,id){
    var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
    if(!d.getElementById(id)){
        js=d.createElement(s);
        js.id=id;
        js.src=p+'://platform.twitter.com/widgets.js';
        fjs.parentNode.insertBefore(js,fjs);
    }
}(document, 'script', 'twitter-wjs');

jQuery(function() {
    jQuery(".starrr").starrr();
    jQuery('#icp-rate').on('starrr:change', function(e, value){
        var url='https://wordpress.org/support/view/plugin-reviews/intelly-countdown?rate=5#postform';
        window.open(url);
    });
    jQuery('#rate-box').show();
});
