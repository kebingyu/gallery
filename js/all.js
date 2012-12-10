
/* zebra dialog js */
;(function(c){c.Zebra_Dialog=function(g,j){var o={animation_speed:250,auto_close:!1,buttons:!0,custom_class:!1,keyboard:!0,message:"",modal:!0,overlay_close:!0,overlay_opacity:0.9,position:"center",title:"",type:"information",vcenter_short_message:!0,width:0,onClose:null},a=this;a.settings={};options={};"string"==typeof g&&(options.message=g);if("object"==typeof g||"object"==typeof j)options=c.extend(options,"object"==typeof g?g:j);a.init=function(){a.settings=c.extend({},o,options);a.isIE6=c.browser.msie&& parseInt(c.browser.version,10)==6||false;if(a.settings.modal){a.overlay=jQuery("<div>",{"class":"ZebraDialogOverlay"}).css({position:a.isIE6?"absolute":"fixed",left:0,top:0,opacity:a.settings.overlay_opacity,"z-index":1E3});a.settings.overlay_close&&a.overlay.bind("click",function(){a.close()});a.overlay.appendTo("body")}a.dialog=jQuery("<div>",{"class":"ZebraDialog"+(a.settings.custom_class?" "+a.settings.custom_class:"")}).css({position:a.isIE6?"absolute":"fixed",left:0,top:0,"z-index":1001,visibility:"hidden"}); !a.settings.buttons&&a.settings.auto_close&&a.dialog.attr("id","ZebraDialog_"+Math.floor(Math.random()*9999999));var b=parseInt(a.settings.width);!isNaN(b)&&b==a.settings.width&&b.toString()==a.settings.width.toString()&&b>0&&a.dialog.css({width:a.settings.width});a.settings.title&&jQuery("<h3>",{"class":"ZebraDialog_Title"}).html(a.settings.title).appendTo(a.dialog);a.message=jQuery("<div>",{"class":"ZebraDialog_Body"+(k()!=""?" ZebraDialog_Icon ZebraDialog_"+k():"")});a.settings.vcenter_short_message? jQuery("<div>").html(a.settings.message).appendTo(a.message):a.message.html(a.settings.message);a.message.appendTo(a.dialog);if(a.settings.buttons!==true&&!c.isArray(a.settings.buttons))b=false;else{if(a.settings.buttons===true)switch(a.settings.type){case "question":a.settings.buttons=["Yes","No"];break;default:a.settings.buttons=["Ok"]}b=a.settings.buttons.reverse()}if(b){var d=jQuery("<div>",{"class":"ZebraDialog_Buttons"}).appendTo(a.dialog);c.each(b,function(b,e){var h=jQuery("<a>",{href:"javascript:void(0)", "class":"ZebraDialog_Button"+b});c.isPlainObject(e)?h.html(e.caption):h.html(e);h.bind("click",function(){void 0!=e.callback&&e.callback(a.dialog);a.close(void 0!=e.caption?e.caption:e)});h.appendTo(d)});jQuery("<div>",{style:"clear:both"}).appendTo(d)}a.dialog.appendTo("body");c(window).bind("resize",i);a.settings.keyboard&&c(document).bind("keyup",l);a.isIE6&&c(window).bind("scroll",m);if(a.settings.auto_close!==false){a.dialog.bind("click",function(){clearTimeout(a.timeout);a.close()});a.timeout= setTimeout(a.close,a.settings.auto_close)}i();return a};a.close=function(b){a.settings.keyboard&&c(document).unbind("keyup",l);a.isIE6&&c(window).unbind("scroll",m);c(window).unbind("resize",i);a.overlay&&a.overlay.animate({opacity:0},a.settings.animation_speed,function(){a.overlay.remove()});a.dialog.animate({top:0,opacity:0},a.settings.animation_speed,function(){a.dialog.remove();if(a.settings.onClose&&typeof a.settings.onClose=="function")a.settings.onClose(void 0!=b?b:"")})};var i=function(){var b= c(window).width(),d=c(window).height(),f=a.dialog.width(),e=a.dialog.height(),f={left:0,top:0,right:b-f,bottom:d-e,center:(b-f)/2,middle:(d-e)/2};a.dialog_left=void 0;a.dialog_top=void 0;a.settings.modal&&a.overlay.css({width:b,height:d});if(c.isArray(a.settings.position)&&a.settings.position.length==2&&typeof a.settings.position[0]=="string"&&a.settings.position[0].match(/^(left|right|center)[\s0-9\+\-]*$/)&&typeof a.settings.position[1]=="string"&&a.settings.position[1].match(/^(top|bottom|middle)[\s0-9\+\-]*$/)){a.settings.position[0]= a.settings.position[0].toLowerCase();a.settings.position[1]=a.settings.position[1].toLowerCase();c.each(f,function(b,c){for(var d=0;d<2;d++){var e=a.settings.position[d].replace(b,c);if(e!=a.settings.position[d])d==0?a.dialog_left=eval(e):a.dialog_top=eval(e)}})}if(void 0==a.dialog_left||void 0==a.dialog_top){a.dialog_left=f.center;a.dialog_top=f.middle}if(a.settings.vcenter_short_message){b=a.message.find("div:first");d=b.height();f=a.message.height();d<f&&b.css({"margin-top":(f-d)/2})}a.dialog.css({left:a.dialog_left, top:a.dialog_top,visibility:"visible"});a.dialog.find("a[class^=ZebraDialog_Button]:first").focus();a.isIE6&&setTimeout(n,500)},n=function(){var b=c(window).scrollTop(),d=c(window).scrollLeft();a.settings.modal&&a.overlay.css({top:b,left:d});a.dialog.css({left:a.dialog_left+d,top:a.dialog_top+b})},k=function(){switch(a.settings.type){case "confirmation":case "error":case "information":case "question":case "warning":return a.settings.type.charAt(0).toUpperCase()+a.settings.type.slice(1).toLowerCase(); default:return false}},l=function(b){b.which==27&&a.close();return true},m=function(){n()};return a.init()}})(jQuery);

// Formly v1.0 by Daniel Raftery
//
// http://thrivingkings.com/formly
// http://twitter.com/ThrivingKings
(function($)
	{
	
	$.fn.formly = function(options, callback) 
		{
		// Default settings
		var settings =
			{
			'theme'		:	'Base',
			'onBlur'	:	true
			};
		
		if(options)
			{ $.extend(settings, options); }
		
		// Form name, very important!
		var formName = this.attr('id');
		if(!formName)
			{ 
			// If no name, make a random one
			formName = Math.ceil(Math.random()*5000); 
			this.attr('id', formName);
			}
		this.append('<div style="clear:both;"></div><div class="formlyAlerts"></div>');
		this.addClass('formlyWrapper-' + settings['theme']);
		if(this.attr('width'))
			{ this.css('width', this.attr('width')); }
		
		if(this.attr('subtitle') || this.attr('title') || this.attr('data-title'))
			{ this.prepend('<hr/>'); }
		if(this.attr('subtitle'))
			{ this.prepend('<h2>' + this.attr('subtitle') + '</h2>'); }
		if(this.attr('title'))
			{ this.prepend('<h1>' + this.attr('title') + '</h1>'); }
		if(this.attr('data-title'))
			{ this.prepend('<h1>' + this.attr('data-title') + '</h1>'); }
		
		this.children().each(function(index, item)
			{
			// Placeholder text
			if($(item).attr('place'))
				{
				if($(item).attr('type')=='password')
					{
					// Password fields with placeholders
					var hID = 'pwPlace-' + $(item).attr('name');
					$(item).after('<input type="text" id="' + hID + '" value="' + $(item).attr('place') + '" class="formlyPWPlaces" />');
					$('#' + hID).css('color', '#bbb');
					$(item).hide();
					$('#' + hID).show();
					
					// Focus and blur must be handled independently for variables sake
					$('#' + hID).focus(function()
						{
						$('#' + hID).hide();
						$(item).show();
						$(item).focus();
						});
					$(item).blur(function()
						{
						if(!$(item).val())
							{
							$('#' + hID).show();
							$(item).hide();
							}
						});
					}
				else
					{
					$(item).val($(item).attr('place'));
					$(item).css('color', '#bbb');
					}
				}
			
			$(item).blur(function()
				{
				// Placeholder text
				if(!$(item).val() || $(item).val()==$(item).attr('pre-fix'))
					{ 
					if($(item).attr('type')!='password')
						{
						$(item).val($(item).attr('place')); 
						$(item).css('color', '#bbb');
						}
					}
				if($(item).attr('pre-fix'))
					{
					var originalVal = $(item).val();
					var thePrefix = $(item).attr('pre-fix');
					if(thePrefix.length==1)
						{
						if(originalVal.charAt(0) != thePrefix && $(item).val() != $(item).attr('place'))
							{ $(item).val(thePrefix + originalVal); }
						}
					else
						{
						if(originalVal.indexOf(thePrefix) == -1 && $(item).val() != $(item).attr('place'))
							{ $(item).val(thePrefix + originalVal); }
						}
					}
				if(settings['onBlur'])
					{
					// Validation
					if($(item).attr('validate'))
						{ functions.validate(item); }
					// Required
					if($(item).attr('require'))
						{ functions.require(item); }
					// Match
					if($(item).attr('match'))
						{ functions.match(item); }
					}
				});
			
			// Focus actions
			$(item).focus(function()
				{
				// Placeholder
				if($(item).attr('place'))
					{
					if($(item).val()==$(item).attr('place'))
						{ 
						$(item).val(''); 
						$(item).css('color', '');
						}
					}
				// Prefixes
				if($(item).attr('pre-fix') && !$(item).val())
					{
					$(item).val('');
					$(item).val($(item).attr('pre-fix'));
					}
				});

				
			// Reset button
			$('#' + formName).find('input:reset').click(function(item)
				{
				item.preventDefault();
				$('#' + formName).find('input:text, textarea, input:password, input:checkbox, input:radio').each(function()
					{
					$(this).css('border-color', '');
					
					if($(this).is(':checked'))
						{ $(this).attr('checked', false); }
					
					if($(this).attr('place'))
						{
						if($(this).attr('type')!='password')
							{
							$(this).val($(this).attr('place'));
							$(this).css('color', '#bbb');
							}
						else
							{ 
							if($(this).hasClass('formlyPWPlaces'))
								{
								$(this).show();
								$(this).prev('input').hide();
								}
							else
								{ $(this).val(''); }
							}
						}
					else
						{
						if($(this).hasClass('formlyPWPlaces'))
							{
							$(this).show();
							$(this).prev('input').hide();
							}
						else
							{ $(this).val(''); }
						}
					});
				$('#' + formName).find('.formlyAlert').each(function()
					{
					$(this).fadeOut(function()
						{ $(this).remove() }); 
					});
				});
			});
			
			// Submit button
			this.submit(function(item)
				{
				var canSubmit = true;
				$(this).find('input').each(function()
					{
					if($(this).attr('require'))
						{
						if(!functions.require(this)) 
							{ canSubmit = false; }
						}
					if($(this).attr('validate'))
						{
						if(!functions.validate(this)) 
							{ canSubmit = false; }
						}
					// Match
					if($(this).attr('match'))
						{
						if(!functions.match(this)) 
							{ canSubmit = false; }
						}
					});
				if(!canSubmit)
					{ item.preventDefault(); }
				else
					{
					if(callback)
						{  // Change this to .serializeArray() for JSON
						item.preventDefault();
						callback($(this).serialize());
						}
					}
				});
			
		var functions = 
			{
			validateString : function(type, string)
				{
				// Validate email regular expression
				if(type=='email')
					{
					var filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
					if(filter.test(string))
						{ return true; }
					else
						{ return false; }
					}
				// Validate a simple URL regular expression
				else if(type=='http')
					{
					var filter = /http:\/\/[A-Za-z0-9\.-]{3,}\.[A-Za-z]{2,3}/i
					if(filter.test(string))
						{ return true; }
					else
						{ return false; }
					}
				},
			validate : function(item)
				{
				var alertName = formName + $(item).attr('name');
				if($(item).attr('validate')=='email')
					{
					var valid = functions.validateString('email', $(item).val());
					var validType = 'email address';
					}
				else if($(item).attr('validate')=='http')
					{
					var valid = functions.validateString('http', $(item).val());
					var validType = 'web address';
					}
				if(!valid) 
					{
					if(!$('#' + alertName).is(':visible'))
						{
						$('#' + formName).find('.formlyAlerts').append('<div class="formlyInvalid formlyAlert" id="' + alertName + '">Invalid ' + validType + '</div>')
						$('#' + alertName).fadeIn();
						}
					var borderColor = $('#' + alertName).css('background-color');
					$(item).css('border-color', borderColor);
					if($(item).attr('type')=='password')
						{ $(item).next('.formlyPWPlaces').css('border-color', borderColor); }
					return false;
					}
				else
					{ 
					$('#' + alertName).fadeOut(function()
						{ $(this).remove() }); 
					$(item).css('border-color', '');
					$('.formlyPWPlaces').css('border-color', '');
					return true;
					}
					
				},
			require : function(item)
				{
				var alertName = formName + $(item).attr('name');
				var label = $(item).attr('label')+' ';
				if(label=='undefined '){label='';}
				if(!$(item).val() || $(item).val()==$(item).attr('place')) 
					{
					if(!$('#' + alertName).is(':visible'))
						{
						$('#' + formName).find('.formlyAlerts').append('<div class="formlyRequired formlyAlert" id="' + alertName + '">'+label+'Required</div>')
						$('#' + alertName).fadeIn();
						}
					var borderColor = $('#' + alertName).css('background-color');
					$(item).css('border-color', borderColor);
					if($(item).attr('type')=='password')
						{ $(item).next('.formlyPWPlaces').css('border-color', borderColor); }
					return false;
					}
				else if($(item).attr('type')=='checkbox' && !$(item).is(':checked'))
					{
					if(!$('#' + alertName).is(':visible'))
						{
						$('#' + formName).find('.formlyAlerts').append('<div class="formlyRequired formlyAlert" id="' + alertName + '">'+label+'Required</div>')
						$('#' + alertName).fadeIn();
						$(item).focus();
						}
					var borderColor = $('#' + alertName).css('background-color');
					$(item).css('border-color', borderColor);
					return false;
					}
				else
					{ 
					$('#' + alertName).fadeOut(function()
						{ $(this).remove() }); 
					$(item).css('border-color', '');
					$('.formlyPWPlaces').css('border-color', '');
					return true;
					}
				},
			match : function(item)
				{
				var alertName = formName + $(item).attr('name');
				var label = $(item).attr('label')+' ';
				if(label=='undefined '){label='';}
				var toMatch = $(item).attr('match');
				if($(item).val() != $('#' + formName).find('input[name=' + toMatch + ']').val() || !$(item).val())
					{
					if(!$('#' + alertName).is(':visible'))
						{
						$('#' + formName).find('.formlyAlerts').append('<div class="formlyInvalid formlyAlert" id="' + alertName + '">'+label+'Does not match</div>')
						$('#' + alertName).fadeIn();
						}
					var borderColor = $('#' + alertName).css('background-color');
					$(item).css('border-color', borderColor);
					if($(item).attr('type')=='password')
						{ $(item).next('.formlyPWPlaces').css('border-color', borderColor); }
					return false;
					}
				else
					{ 
					$('#' + alertName).fadeOut(function()
						{ $(this).remove() }); 
					$(item).css('border-color', '');
					$('.formlyPWPlaces').css('border-color', '');
					return true;
					}
				}
			};
		};
		
})( jQuery );

// Chosen, a Select Box Enhancer for jQuery and Protoype
// by Patrick Filler for Harvest, http://getharvest.com
// 
// Version 0.9.8
// Full source at https://github.com/harvesthq/chosen
// Copyright (c) 2011 Harvest http://getharvest.com

// MIT License, https://github.com/harvesthq/chosen/blob/master/LICENSE.md
// This file is generated by `cake build`, do not edit it by hand.
((function(){var a;a=function(){function a(){this.options_index=0,this.parsed=[]}return a.prototype.add_node=function(a){return a.nodeName==="OPTGROUP"?this.add_group(a):this.add_option(a)},a.prototype.add_group=function(a){var b,c,d,e,f,g;b=this.parsed.length,this.parsed.push({array_index:b,group:!0,label:a.label,children:0,disabled:a.disabled}),f=a.childNodes,g=[];for(d=0,e=f.length;d<e;d++)c=f[d],g.push(this.add_option(c,b,a.disabled));return g},a.prototype.add_option=function(a,b,c){if(a.nodeName==="OPTION")return a.text!==""?(b!=null&&(this.parsed[b].children+=1),this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,value:a.value,text:a.text,html:a.innerHTML,selected:a.selected,disabled:c===!0?c:a.disabled,group_array_index:b,classes:a.className,style:a.style.cssText})):this.parsed.push({array_index:this.parsed.length,options_index:this.options_index,empty:!0}),this.options_index+=1},a}(),a.select_to_array=function(b){var c,d,e,f,g;d=new a,g=b.childNodes;for(e=0,f=g.length;e<f;e++)c=g[e],d.add_node(c);return d.parsed},this.SelectParser=a})).call(this),function(){var a,b;b=this,a=function(){function a(a,b){this.form_field=a,this.options=b!=null?b:{},this.set_default_values(),this.is_multiple=this.form_field.multiple,this.set_default_text(),this.setup(),this.set_up_html(),this.register_observers(),this.finish_setup()}return a.prototype.set_default_values=function(){var a=this;return this.click_test_action=function(b){return a.test_active_click(b)},this.activate_action=function(b){return a.activate_field(b)},this.active_field=!1,this.mouse_on_container=!1,this.results_showing=!1,this.result_highlighted=null,this.result_single_selected=null,this.allow_single_deselect=this.options.allow_single_deselect!=null&&this.form_field.options[0]!=null&&this.form_field.options[0].text===""?this.options.allow_single_deselect:!1,this.disable_search_threshold=this.options.disable_search_threshold||0,this.search_contains=this.options.search_contains||!1,this.choices=0,this.single_backstroke_delete=this.options.single_backstroke_delete||!1,this.max_selected_options=this.options.max_selected_options||Infinity},a.prototype.set_default_text=function(){return this.form_field.getAttribute("data-placeholder")?this.default_text=this.form_field.getAttribute("data-placeholder"):this.is_multiple?this.default_text=this.options.placeholder_text_multiple||this.options.placeholder_text||"Select Some Options":this.default_text=this.options.placeholder_text_single||this.options.placeholder_text||"Select an Option",this.results_none_found=this.form_field.getAttribute("data-no_results_text")||this.options.no_results_text||"No results match"},a.prototype.mouse_enter=function(){return this.mouse_on_container=!0},a.prototype.mouse_leave=function(){return this.mouse_on_container=!1},a.prototype.input_focus=function(a){var b=this;if(!this.active_field)return setTimeout(function(){return b.container_mousedown()},50)},a.prototype.input_blur=function(a){var b=this;if(!this.mouse_on_container)return this.active_field=!1,setTimeout(function(){return b.blur_test()},100)},a.prototype.result_add_option=function(a){var b,c;return a.disabled?"":(a.dom_id=this.container_id+"_o_"+a.array_index,b=a.selected&&this.is_multiple?[]:["active-result"],a.selected&&b.push("result-selected"),a.group_array_index!=null&&b.push("group-option"),a.classes!==""&&b.push(a.classes),c=a.style.cssText!==""?' style="'+a.style+'"':"",'<li id="'+a.dom_id+'" class="'+b.join(" ")+'"'+c+">"+a.html+"</li>")},a.prototype.results_update_field=function(){return this.is_multiple||this.results_reset_cleanup(),this.result_clear_highlight(),this.result_single_selected=null,this.results_build()},a.prototype.results_toggle=function(){return this.results_showing?this.results_hide():this.results_show()},a.prototype.results_search=function(a){return this.results_showing?this.winnow_results():this.results_show()},a.prototype.keyup_checker=function(a){var b,c;b=(c=a.which)!=null?c:a.keyCode,this.search_field_scale();switch(b){case 8:if(this.is_multiple&&this.backstroke_length<1&&this.choices>0)return this.keydown_backstroke();if(!this.pending_backstroke)return this.result_clear_highlight(),this.results_search();break;case 13:a.preventDefault();if(this.results_showing)return this.result_select(a);break;case 27:return this.results_showing&&this.results_hide(),!0;case 9:case 38:case 40:case 16:case 91:case 17:break;default:return this.results_search()}},a.prototype.generate_field_id=function(){var a;return a=this.generate_random_id(),this.form_field.id=a,a},a.prototype.generate_random_char=function(){var a,b,c;return a="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ",c=Math.floor(Math.random()*a.length),b=a.substring(c,c+1)},a}(),b.AbstractChosen=a}.call(this),function(){var a,b,c,d,e=Object.prototype.hasOwnProperty,f=function(a,b){function d(){this.constructor=a}for(var c in b)e.call(b,c)&&(a[c]=b[c]);return d.prototype=b.prototype,a.prototype=new d,a.__super__=b.prototype,a};d=this,a=jQuery,a.fn.extend({chosen:function(c){return!a.browser.msie||a.browser.version!=="6.0"&&a.browser.version!=="7.0"?this.each(function(d){var e;e=a(this);if(!e.hasClass("chzn-done"))return e.data("chosen",new b(this,c))}):this}}),b=function(b){function e(){e.__super__.constructor.apply(this,arguments)}return f(e,b),e.prototype.setup=function(){return this.form_field_jq=a(this.form_field),this.current_value=this.form_field_jq.val(),this.is_rtl=this.form_field_jq.hasClass("chzn-rtl")},e.prototype.finish_setup=function(){return this.form_field_jq.addClass("chzn-done")},e.prototype.set_up_html=function(){var b,d,e,f;return this.container_id=this.form_field.id.length?this.form_field.id.replace(/[^\w]/g,"_"):this.generate_field_id(),this.container_id+="_chzn",this.f_width=this.form_field_jq.outerWidth(),b=a("<div />",{id:this.container_id,"class":"chzn-container"+(this.is_rtl?" chzn-rtl":""),style:"width: "+this.f_width+"px;"}),this.is_multiple?b.html('<ul class="chzn-choices"><li class="search-field"><input type="text" value="'+this.default_text+'" class="default" autocomplete="off" style="width:25px;" /></li></ul><div class="chzn-drop" style="left:-9000px;"><ul class="chzn-results"></ul></div>'):b.html('<a href="javascript:void(0)" class="chzn-single chzn-default"><span>'+this.default_text+'</span><div><b></b></div></a><div class="chzn-drop" style="left:-9000px;"><div class="chzn-search"><input type="text" autocomplete="off" /></div><ul class="chzn-results"></ul></div>'),this.form_field_jq.hide().after(b),this.container=a("#"+this.container_id),this.container.addClass("chzn-container-"+(this.is_multiple?"multi":"single")),this.dropdown=this.container.find("div.chzn-drop").first(),d=this.container.height(),e=this.f_width-c(this.dropdown),this.dropdown.css({width:e+"px",top:d+"px"}),this.search_field=this.container.find("input").first(),this.search_results=this.container.find("ul.chzn-results").first(),this.search_field_scale(),this.search_no_results=this.container.find("li.no-results").first(),this.is_multiple?(this.search_choices=this.container.find("ul.chzn-choices").first(),this.search_container=this.container.find("li.search-field").first()):(this.search_container=this.container.find("div.chzn-search").first(),this.selected_item=this.container.find(".chzn-single").first(),f=e-c(this.search_container)-c(this.search_field),this.search_field.css({width:f+"px"})),this.results_build(),this.set_tab_index(),this.form_field_jq.trigger("liszt:ready",{chosen:this})},e.prototype.register_observers=function(){var a=this;return this.container.mousedown(function(b){return a.container_mousedown(b)}),this.container.mouseup(function(b){return a.container_mouseup(b)}),this.container.mouseenter(function(b){return a.mouse_enter(b)}),this.container.mouseleave(function(b){return a.mouse_leave(b)}),this.search_results.mouseup(function(b){return a.search_results_mouseup(b)}),this.search_results.mouseover(function(b){return a.search_results_mouseover(b)}),this.search_results.mouseout(function(b){return a.search_results_mouseout(b)}),this.form_field_jq.bind("liszt:updated",function(b){return a.results_update_field(b)}),this.search_field.blur(function(b){return a.input_blur(b)}),this.search_field.keyup(function(b){return a.keyup_checker(b)}),this.search_field.keydown(function(b){return a.keydown_checker(b)}),this.is_multiple?(this.search_choices.click(function(b){return a.choices_click(b)}),this.search_field.focus(function(b){return a.input_focus(b)})):this.container.click(function(a){return a.preventDefault()})},e.prototype.search_field_disabled=function(){this.is_disabled=this.form_field_jq[0].disabled;if(this.is_disabled)return this.container.addClass("chzn-disabled"),this.search_field[0].disabled=!0,this.is_multiple||this.selected_item.unbind("focus",this.activate_action),this.close_field();this.container.removeClass("chzn-disabled"),this.search_field[0].disabled=!1;if(!this.is_multiple)return this.selected_item.bind("focus",this.activate_action)},e.prototype.container_mousedown=function(b){var c;if(!this.is_disabled)return c=b!=null?a(b.target).hasClass("search-choice-close"):!1,b&&b.type==="mousedown"&&!this.results_showing&&b.stopPropagation(),!this.pending_destroy_click&&!c?(this.active_field?!this.is_multiple&&b&&(a(b.target)[0]===this.selected_item[0]||a(b.target).parents("a.chzn-single").length)&&(b.preventDefault(),this.results_toggle()):(this.is_multiple&&this.search_field.val(""),a(document).click(this.click_test_action),this.results_show()),this.activate_field()):this.pending_destroy_click=!1},e.prototype.container_mouseup=function(a){if(a.target.nodeName==="ABBR"&&!this.is_disabled)return this.results_reset(a)},e.prototype.blur_test=function(a){if(!this.active_field&&this.container.hasClass("chzn-container-active"))return this.close_field()},e.prototype.close_field=function(){return a(document).unbind("click",this.click_test_action),this.is_multiple||(this.selected_item.attr("tabindex",this.search_field.attr("tabindex")),this.search_field.attr("tabindex",-1)),this.active_field=!1,this.results_hide(),this.container.removeClass("chzn-container-active"),this.winnow_results_clear(),this.clear_backstroke(),this.show_search_field_default(),this.search_field_scale()},e.prototype.activate_field=function(){return!this.is_multiple&&!this.active_field&&(this.search_field.attr("tabindex",this.selected_item.attr("tabindex")),this.selected_item.attr("tabindex",-1)),this.container.addClass("chzn-container-active"),this.active_field=!0,this.search_field.val(this.search_field.val()),this.search_field.focus()},e.prototype.test_active_click=function(b){return a(b.target).parents("#"+this.container_id).length?this.active_field=!0:this.close_field()},e.prototype.results_build=function(){var a,b,c,e,f;this.parsing=!0,this.results_data=d.SelectParser.select_to_array(this.form_field),this.is_multiple&&this.choices>0?(this.search_choices.find("li.search-choice").remove(),this.choices=0):this.is_multiple||(this.selected_item.addClass("chzn-default").find("span").text(this.default_text),this.form_field.options.length<=this.disable_search_threshold?this.container.addClass("chzn-container-single-nosearch"):this.container.removeClass("chzn-container-single-nosearch")),a="",f=this.results_data;for(c=0,e=f.length;c<e;c++)b=f[c],b.group?a+=this.result_add_group(b):b.empty||(a+=this.result_add_option(b),b.selected&&this.is_multiple?this.choice_build(b):b.selected&&!this.is_multiple&&(this.selected_item.removeClass("chzn-default").find("span").text(b.text),this.allow_single_deselect&&this.single_deselect_control_build()));return this.search_field_disabled(),this.show_search_field_default(),this.search_field_scale(),this.search_results.html(a),this.parsing=!1},e.prototype.result_add_group=function(b){return b.disabled?"":(b.dom_id=this.container_id+"_g_"+b.array_index,'<li id="'+b.dom_id+'" class="group-result">'+a("<div />").text(b.label).html()+"</li>")},e.prototype.result_do_highlight=function(a){var b,c,d,e,f;if(a.length){this.result_clear_highlight(),this.result_highlight=a,this.result_highlight.addClass("highlighted"),d=parseInt(this.search_results.css("maxHeight"),10),f=this.search_results.scrollTop(),e=d+f,c=this.result_highlight.position().top+this.search_results.scrollTop(),b=c+this.result_highlight.outerHeight();if(b>=e)return this.search_results.scrollTop(b-d>0?b-d:0);if(c<f)return this.search_results.scrollTop(c)}},e.prototype.result_clear_highlight=function(){return this.result_highlight&&this.result_highlight.removeClass("highlighted"),this.result_highlight=null},e.prototype.results_show=function(){var a;if(!this.is_multiple)this.selected_item.addClass("chzn-single-with-drop"),this.result_single_selected&&this.result_do_highlight(this.result_single_selected);else if(this.max_selected_options<=this.choices)return this.form_field_jq.trigger("liszt:maxselected",{chosen:this}),!1;return a=this.is_multiple?this.container.height():this.container.height()-1,this.form_field_jq.trigger("liszt:showing_dropdown",{chosen:this}),this.dropdown.css({top:a+"px",left:0}),this.results_showing=!0,this.search_field.focus(),this.search_field.val(this.search_field.val()),this.winnow_results()},e.prototype.results_hide=function(){return this.is_multiple||this.selected_item.removeClass("chzn-single-with-drop"),this.result_clear_highlight(),this.form_field_jq.trigger("liszt:hiding_dropdown",{chosen:this}),this.dropdown.css({left:"-9000px"}),this.results_showing=!1},e.prototype.set_tab_index=function(a){var b;if(this.form_field_jq.attr("tabindex"))return b=this.form_field_jq.attr("tabindex"),this.form_field_jq.attr("tabindex",-1),this.is_multiple?this.search_field.attr("tabindex",b):(this.selected_item.attr("tabindex",b),this.search_field.attr("tabindex",-1))},e.prototype.show_search_field_default=function(){return this.is_multiple&&this.choices<1&&!this.active_field?(this.search_field.val(this.default_text),this.search_field.addClass("default")):(this.search_field.val(""),this.search_field.removeClass("default"))},e.prototype.search_results_mouseup=function(b){var c;c=a(b.target).hasClass("active-result")?a(b.target):a(b.target).parents(".active-result").first();if(c.length)return this.result_highlight=c,this.result_select(b)},e.prototype.search_results_mouseover=function(b){var c;c=a(b.target).hasClass("active-result")?a(b.target):a(b.target).parents(".active-result").first();if(c)return this.result_do_highlight(c)},e.prototype.search_results_mouseout=function(b){if(a(b.target).hasClass("active-result"))return this.result_clear_highlight()},e.prototype.choices_click=function(b){b.preventDefault();if(this.active_field&&!a(b.target).hasClass("search-choice")&&!this.results_showing)return this.results_show()},e.prototype.choice_build=function(b){var c,d,e=this;return this.is_multiple&&this.max_selected_options<=this.choices?(this.form_field_jq.trigger("liszt:maxselected",{chosen:this}),!1):(c=this.container_id+"_c_"+b.array_index,this.choices+=1,this.search_container.before('<li class="search-choice" id="'+c+'"><span>'+b.html+'</span><a href="javascript:void(0)" class="search-choice-close" rel="'+b.array_index+'"></a></li>'),d=a("#"+c).find("a").first(),d.click(function(a){return e.choice_destroy_link_click(a)}))},e.prototype.choice_destroy_link_click=function(b){return b.preventDefault(),this.is_disabled?b.stopPropagation:(this.pending_destroy_click=!0,this.choice_destroy(a(b.target)))},e.prototype.choice_destroy=function(a){return this.choices-=1,this.show_search_field_default(),this.is_multiple&&this.choices>0&&this.search_field.val().length<1&&this.results_hide(),this.result_deselect(a.attr("rel")),a.parents("li").first().remove()},e.prototype.results_reset=function(){this.form_field.options[0].selected=!0,this.selected_item.find("span").text(this.default_text),this.is_multiple||this.selected_item.addClass("chzn-default"),this.show_search_field_default(),this.results_reset_cleanup(),this.form_field_jq.trigger("change");if(this.active_field)return this.results_hide()},e.prototype.results_reset_cleanup=function(){return this.selected_item.find("abbr").remove()},e.prototype.result_select=function(a){var b,c,d,e;if(this.result_highlight)return b=this.result_highlight,c=b.attr("id"),this.result_clear_highlight(),this.is_multiple?this.result_deactivate(b):(this.search_results.find(".result-selected").removeClass("result-selected"),this.result_single_selected=b,this.selected_item.removeClass("chzn-default")),b.addClass("result-selected"),e=c.substr(c.lastIndexOf("_")+1),d=this.results_data[e],d.selected=!0,this.form_field.options[d.options_index].selected=!0,this.is_multiple?this.choice_build(d):(this.selected_item.find("span").first().text(d.text),this.allow_single_deselect&&this.single_deselect_control_build()),(!a.metaKey||!this.is_multiple)&&this.results_hide(),this.search_field.val(""),(this.is_multiple||this.form_field_jq.val()!==this.current_value)&&this.form_field_jq.trigger("change",{selected:this.form_field.options[d.options_index].value}),this.current_value=this.form_field_jq.val(),this.search_field_scale()},e.prototype.result_activate=function(a){return a.addClass("active-result")},e.prototype.result_deactivate=function(a){return a.removeClass("active-result")},e.prototype.result_deselect=function(b){var c,d;return d=this.results_data[b],d.selected=!1,this.form_field.options[d.options_index].selected=!1,c=a("#"+this.container_id+"_o_"+b),c.removeClass("result-selected").addClass("active-result").show(),this.result_clear_highlight(),this.winnow_results(),this.form_field_jq.trigger("change",{deselected:this.form_field.options[d.options_index].value}),this.search_field_scale()},e.prototype.single_deselect_control_build=function(){if(this.allow_single_deselect&&this.selected_item.find("abbr").length<1)return this.selected_item.find("span").first().after('<abbr class="search-choice-close"></abbr>')},e.prototype.winnow_results=function(){var b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s;this.no_results_clear(),j=0,k=this.search_field.val()===this.default_text?"":a("<div/>").text(a.trim(this.search_field.val())).html(),g=this.search_contains?"":"^",f=new RegExp(g+k.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),"i"),n=new RegExp(k.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&"),"i"),s=this.results_data;for(o=0,q=s.length;o<q;o++){c=s[o];if(!c.disabled&&!c.empty)if(c.group)a("#"+c.dom_id).css("display","none");else if(!this.is_multiple||!c.selected){b=!1,i=c.dom_id,h=a("#"+i);if(f.test(c.html))b=!0,j+=1;else if(c.html.indexOf(" ")>=0||c.html.indexOf("[")===0){e=c.html.replace(/\[|\]/g,"").split(" ");if(e.length)for(p=0,r=e.length;p<r;p++)d=e[p],f.test(d)&&(b=!0,j+=1)}b?(k.length?(l=c.html.search(n),m=c.html.substr(0,l+k.length)+"</em>"+c.html.substr(l+k.length),m=m.substr(0,l)+"<em>"+m.substr(l)):m=c.html,h.html(m),this.result_activate(h),c.group_array_index!=null&&a("#"+this.results_data[c.group_array_index].dom_id).css("display","list-item")):(this.result_highlight&&i===this.result_highlight.attr("id")&&this.result_clear_highlight(),this.result_deactivate(h))}}return j<1&&k.length?this.no_results(k):this.winnow_results_set_highlight()},e.prototype.winnow_results_clear=function(){var b,c,d,e,f;this.search_field.val(""),c=this.search_results.find("li"),f=[];for(d=0,e=c.length;d<e;d++)b=c[d],b=a(b),b.hasClass("group-result")?f.push(b.css("display","auto")):!this.is_multiple||!b.hasClass("result-selected")?f.push(this.result_activate(b)):f.push(void 0);return f},e.prototype.winnow_results_set_highlight=function(){var a,b;if(!this.result_highlight){b=this.is_multiple?[]:this.search_results.find(".result-selected.active-result"),a=b.length?b.first():this.search_results.find(".active-result").first();if(a!=null)return this.result_do_highlight(a)}},e.prototype.no_results=function(b){var c;return c=a('<li class="no-results">'+this.results_none_found+' "<span></span>"</li>'),c.find("span").first().html(b),this.search_results.append(c)},e.prototype.no_results_clear=function(){return this.search_results.find(".no-results").remove()},e.prototype.keydown_arrow=function(){var b,c;this.result_highlight?this.results_showing&&(c=this.result_highlight.nextAll("li.active-result").first(),c&&this.result_do_highlight(c)):(b=this.search_results.find("li.active-result").first(),b&&this.result_do_highlight(a(b)));if(!this.results_showing)return this.results_show()},e.prototype.keyup_arrow=function(){var a;if(!this.results_showing&&!this.is_multiple)return this.results_show();if(this.result_highlight)return a=this.result_highlight.prevAll("li.active-result"),a.length?this.result_do_highlight(a.first()):(this.choices>0&&this.results_hide(),this.result_clear_highlight())},e.prototype.keydown_backstroke=function(){return this.pending_backstroke?(this.choice_destroy(this.pending_backstroke.find("a").first()),this.clear_backstroke()):(this.pending_backstroke=this.search_container.siblings("li.search-choice").last(),this.single_backstroke_delete?this.keydown_backstroke():this.pending_backstroke.addClass("search-choice-focus"))},e.prototype.clear_backstroke=function(){return this.pending_backstroke&&this.pending_backstroke.removeClass("search-choice-focus"),this.pending_backstroke=null},e.prototype.keydown_checker=function(a){var b,c;b=(c=a.which)!=null?c:a.keyCode,this.search_field_scale(),b!==8&&this.pending_backstroke&&this.clear_backstroke();switch(b){case 8:this.backstroke_length=this.search_field.val().length;break;case 9:this.results_showing&&!this.is_multiple&&this.result_select(a),this.mouse_on_container=!1;break;case 13:a.preventDefault();break;case 38:a.preventDefault(),this.keyup_arrow();break;case 40:this.keydown_arrow()}},e.prototype.search_field_scale=function(){var b,c,d,e,f,g,h,i,j;if(this.is_multiple){d=0,h=0,f="position:absolute; left: -1000px; top: -1000px; display:none;",g=["font-size","font-style","font-weight","font-family","line-height","text-transform","letter-spacing"];for(i=0,j=g.length;i<j;i++)e=g[i],f+=e+":"+this.search_field.css(e)+";";return c=a("<div />",{style:f}),c.text(this.search_field.val()),a("body").append(c),h=c.width()+25,c.remove(),h>this.f_width-10&&(h=this.f_width-10),this.search_field.css({width:h+"px"}),b=this.container.height(),this.dropdown.css({top:b+"px"})}},e.prototype.generate_random_id=function(){var b;b="sel"+this.generate_random_char()+this.generate_random_char()+this.generate_random_char();while(a("#"+b).length>0)b+=this.generate_random_char();return b},e}(AbstractChosen),c=function(a){var b;return b=a.outerWidth()-a.width()},d.get_side_border_padding=c}.call(this);
$(document).ready(function() {

	/* Sliding Login Begin */	
	// Expand Panel
	$("#open").click(function(){
		$("div#panel").slideDown("slow");
		return false;	
	});	
	
	// Collapse Panel
	$("#close").click(function(){
		$('input.pnl_pwd').val('');
		$("div#panel").slideUp("slow");	
		return false;	
	});		
	
	// Switch buttons from "Log In | Register" to "Close Panel" on click
	$("#toggle a").click(function () {
		$("#toggle a").toggle();
	});		

	// register form
	$('#btn_register').click(function() {
		register();
		return false;
	});

	$('#reg_form').keypress(function (e) {
		if (e.which == 13) {
			register();
			return false;
		}
	});

	isValidateReady('validateRegisterForm');

	// login form
	$('#btn_login').click(function() {
		login();
		return false;
	});

	$('#login_form').keypress(function (e) {
		if (e.which == 13) {
			login();
			return false;
		}
	});

	isValidateReady('validateLoginForm');
	/* Sliding Login End */	

	// register ajax loading event
	$("body").on({
		ajaxStart: function() {
			$(this).addClass("loading");
		},
		ajaxStop: function() {
			$(this).removeClass("loading");
		}    
	});

});

function call_func(func) {
    return this[func].apply(this, Array.prototype.slice.call(arguments, 1));
}

function isPluginReady(plugin, callback, params) {
    if (typeof plugin == 'string' && (eval('typeof ' + plugin) == 'function')
            || (eval('typeof ' + plugin) == 'object')) {
        if (params != undefined) {
            call_func(callback, params);
        } else {
            call_func(callback);
        }
    } else {
        if (params != undefined) {
            window.setTimeout("isPluginReady('" + plugin + "','" + callback
                    + "','" + params + "');", 100);
        } else {
            window.setTimeout("isPluginReady('" + plugin + "','" + callback
                    + "');", 100);
        }
    }
}

function isValidateReady(callback) {
    if (typeof isPluginReady != 'undefined') {
        isPluginReady('jQuery().validate', callback);
    } else {
        window.setTimeout("isValidateReady();", 100);
    }    
}

function validateRegisterForm(){
    $('#reg_form').validate({
        errorLabelContainer: $('#pnl_jserror'),
        messages: {
            signup: {
                required: '* Register: Please enter user name.'
            },
            reg_pwd: {
                required: '* Register: Please enter password.'
            },
            conf_pwd: {
                required: '* Register: Please confirm your password.'
            }, 
            pcode: { 
                required: '* Register: Please enter promotion code.'
            }
        },
        rules: {
            signup: {
                required: true
            },      
            reg_pwd: {
                required: true
            },      
            conf_pwd: {
                required: true
            },
            pcode: {
                required: true
            }
        }
    });
}      


function register() {
	var str = $('#reg_form').serialize();
	$.post('/services/register', str, function(data) {
		if (data.register == 1) {
			$('#pnl_error').val('');
			$('#pnl_jserror').val('');
			window.location.href = '/profile/index';
		} else {
			var html = "";
			if (data.error) {
				for (var i in data.error) {
					html += data.error[i] + '<br/>';
				}
			}
			$('#pnl_error').html(html);
		}
	}, "json");
	return false;
}

function validateLoginForm(){
    $('#login_form').validate({
        errorLabelContainer: $('#pnl_jserror'),
        messages: {
            login: {
                required: '* Login: Please enter user name.'
            },
            login_pwd: {
                required: '* Login: Please enter password.'
            },
        },
        rules: {
            login: {
                required: true
            },      
            login_pwd: {
                required: true
            },      
        }
    });
}      

function login() {
	var str = $('#login_form').serialize();
	$.post('/services/login', str, function(data) {
		if (data.login == 1) {
			$('#pnl_error').val('');
			$('#pnl_jserror').val('');
			window.location.href = '/profile/index';
		} else {
			var html = "";
			if (data.error) {
				for (var i in data.error) {
					html += data.error[i] + '<br/>';
				}
			}
			$('#pnl_error').html(html);
		}
	}, "json");
	return false;
}

function zebra_center(log, duration) {
    new $.Zebra_Dialog(log, {
        'button'     : false,
        'modal'      : false,
        'auto_close' : typeof duration !== 'undefined' ? duration : 2000
    });
}

function zebra_corner(log, duration) {
    new $.Zebra_Dialog(log, {
        'button'     : false,
        'modal'      : false,
        'position'   : ['right - 20', 'top + 60'],
        'auto_close' : typeof duration !== 'undefined' ? duration : 2000
    });
}

function OnFocus (id, value) {
	obj = document.getElementById(id);
	obj.value = (obj.value == value) ? '' : obj.value;
	obj.select();
}

function OnBlur (id, value) {
	obj = document.getElementById(id);
	obj.value = (obj.value == '') ? value : obj.value;				
}
