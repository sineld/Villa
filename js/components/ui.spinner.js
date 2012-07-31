/*
 * jQuery UI Spinner @VERSION
 *
 * Copyright (c) 2009 AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 *
 * http://docs.jquery.com/UI/Spinner
 *
 * Depends:
 *  ui.core.js
 */
(function($) {

// shortcut constants
var hover = 'ui-state-hover',
	active = 'ui-state-active',
	namespace = '.spinner',
	buttonRegex = /hide|auto|fast|slow|(\d+)/,
	buttonDefault = 'show';

$.widget('ui.spinner', {
	_init: function() {		
		this._initOptions();
		
		this._value(this._value() || this.options.value);
				
		this._draw();

		this._mousewheel();
		
		if (this.options.buttons !== buttonDefault) {
			this.buttons.hide();
		}
	},
	_initOptions: function() {
		var self = this,
			options = self.options;
		
		// parse min, max, step, and page based on radix
		// min, max and step pull from attributes if the option is set to null
		$.each({ min: -Number.MAX_VALUE, max: Number.MAX_VALUE, step: 1},
			function(option, defaultValue) {
				options[option] = self._parse(options[option] !== null
					? options[option]
					: self.element.attr(option)
						? self.element.attr(option)
						: defaultValue);
			});
			
		options.page = self._parse(options.page || 1);
		
		options.readOnly = options.readOnly !== null
			? options.readOnly
			: self.element.attr('readonly');
		
		// check for precision in stepping and set _precision as internal
		self._precision = parseInt(options.precision, 10);
		
		if (options.step.toString().indexOf('.') != -1 && self._precision === 0) {
			var s = options.step.toString();
			self._precision = s.slice(s.indexOf('.')+1, s.length).length;
		}
		
		// set currency options
		if (options.currency) {
			this._precision = 2;
			options.radix = 10,
			options.groupSeparator = options.groupSeparator || (options.radixPoint === ',' ? '' : ',');
		}
	},
	_draw: function() {
		var self = this,
			options = self.options;

		var uiSpinner = self.element
			.addClass('ui-spinner-box')
			.attr('autocomplete', 'off') // switch off autocomplete in opera
			.wrap(self._uiSpinnerHtml())
			.parent()
				// add buttons
				.append(self._buttonHtml())
				// add aria properties
				.attr('aria-valuemin',options.min)
				.attr('aria-valuemax',options.max)
				.attr('aria-valuenow',options.value)
				// add behaviours
				.hover(function() {
					if (!options.readOnly && !options.disabled) {
						$(this).addClass(hover);
					}
					self.hovered = true;
					self._hide(false);
				}, function() {
					$(this).removeClass(hover);
					self.hovered = false;
					self._hide(true);
				});

		// TODO: need a better way to exclude IE8 without resorting to $.browser.version
		// fix inline-block issues for IE. Since IE8 supports inline-block we need to exclude it.
		if (!$.support.opacity && uiSpinner.css('display') == 'inline-block' && $.browser.version < 8) {
			uiSpinner.css('display', 'inline');
		}

		// element bindings
		this.element
			// Give the spinner casing a unique id only if one exists in original input 
			// - this should aid targetted customisations if a page contains multiple instances
			.attr('id', function(){
				if (this.id) {
					uiSpinner.attr('id', 'ui-spinner-'+ this.id);
				}
			})
			.bind('keydown'+namespace, function(event) {
				self._start(event);
				return self._keydown(event);
			})
			.bind('keyup'+namespace, function(event) {
				self._stop(event);
				self._change(event);
			})
			.bind('focus'+namespace, function() {
				if (options.readOnly) {
					self.element.blur();
					return false;
				}
				uiSpinner.addClass(active);
				self.focused = true;
			})
			.bind('blur'+namespace, function(event) {
				if (!self.hovered) {
					uiSpinner.removeClass(active);
				}		
				self.focused = false;
				self._cleanUp();
			});		

		// force width if passed through options
		if (options.width) {
			this.element.width(options.width);
		}

		// disable spinner if element was already disabled
		if (options.disabled) {
			this.disable();
		}
		
		// button bindings
		this.buttons = uiSpinner.find('.ui-spinner-button')
			.bind('mousedown', function(event) {				
				self._start(event);
				self._repeat(null, $(this).hasClass('ui-spinner-up') ? 1 : -1, event);
				
				if (!self.options.disabled && !self.options.readOnly) {
					$(this).addClass(active);
					uiSpinner.addClass(active);
				}
			})
			.bind('mouseup', function(event) {
				if (self.counter == 1) {
					self._spin(($(this).hasClass('ui-spinner-up') ? 1 : -1) * options.step, event);
				}
				self._stop(event);
				
				$(this).removeClass(active);
			})
			.bind('click', function(event) {
				self._change(event);
			})
			// mousedown/mouseup capture first click, now handle second click
			.bind('dblclick', function(event) {
				if ($.browser.msie) {
					$(this).removeClass(active);
					self._spin(($(this).hasClass('ui-spinner-up') ? 1 : -1) * options.step, event);
					self._stop(event);
					self._change(event);
				}
			})
			.hover(function() {
				if (!self.options.disabled && !self.options.readOnly) {
					$(this).addClass(hover);					
				}
			}, function(event) {
				$(this).removeClass(active + ' ' + hover);
				if (self.timer) {
					self._stop(event);
				}
			});
					
		self.uiSpinner = uiSpinner;
	},
	_uiSpinnerHtml: function() {
		return '<div role="spinbutton" class="ui-spinner ui-state-default ui-widget ui-widget-content ui-corner-all ' + 
				(this.options.spinnerClass || '') + 
				' ui-spinner-' + this.options.dir + 
				'"></div>';
	},
	_buttonHtml: function() {
		return '<a class="ui-spinner-button ui-spinner-up ui-state-default ui-corner-t' + this.options.dir.substr(-1,1) + 
				'"><span class="ui-spinner-button-inner"><span class="ui-icon ui-icon-triangle-1-n">&#9650;</span></span></a>' +
				'<a class="ui-spinner-button ui-spinner-down ui-state-default ui-corner-b' + this.options.dir.substr(-1,1) + 
				'"><span class="ui-spinner-button-inner"><span class="ui-icon ui-icon-triangle-1-s">&#9660;</span></span></a>';
	},
	_constrain: function() {
		var value = this._value();
		if (value < this.options.min) {
			this._value(this.options.min);
		}
		if (value > this.options.max) {
			this._value(this.options.max);
		}
	},
	_cleanUp: function() {
		this._value(this._value());
		this._constrain();
	},
	_start: function(event) {
		if (!this.spinning && !this.options.readOnly) {
			if (!this.counter) {
				this.counter = 1;
			}
			this.spinning = true;
			this._trigger('start', event, {
				value: this.value()
			});
		}
	},
	_spin: function(step, event) {
		if (this.options.disabled || this.options.readOnly) {
			return;
		}
		if (!this.counter) {
			this.counter = 1;
		}
		
		this._value(this._value() + step * (this.options.incremental && this.counter > 100
			? this.counter > 200
				? 100 
				: 10
			: 1));
			
		this._constrain();
		this.counter++;
		
		this._trigger('spin', event, { value: this.value() });
	},
	_stop: function(event) {
		this.counter = 0;
		if (this.timer) {
			window.clearInterval(this.timer);
		}
		this.element[0].focus();
		this.spinning = false;
		this._trigger('stop', event, { value: this.value() });
	},
	_change: function(event) {
		this._trigger('change', event, { value: this.value() });
	},
	_repeat: function(i, steps, event) {
		var self = this;
		i = i || 100;
		if (this.timer) {
			window.clearInterval(this.timer);
		}
		this.timer = window.setInterval(function() {
			self._spin(steps*self.options.step, event);
			if (self.options.incremental && self.counter > 20) {
				self._repeat(20, steps, event);
			}
		}, i);
		if (event.originalEvent && event.originalEvent.type === 'keydown') {
			this._spin(steps*this.options.step, event);			
		}
	},
	_keydown: function(event) {
		var o = this.options,
			KEYS = $.ui.keyCode;

		switch (event.keyCode) {
			case KEYS.UP: 			this._repeat(null, event.shiftKey ? o.page : 1, event); break;
			case KEYS.DOWN: 		this._repeat(null, event.shiftKey ? -o.page : -1, event); break;
			case KEYS.PAGE_UP: 		this._repeat(null, o.page, event); break;
			case KEYS.PAGE_DOWN: 	this._repeat(null, -o.page, event); break;
			
			case KEYS.HOME: 		this._value(o.min); break;
			case KEYS.END: 			this._value(o.max); break;
			
			case KEYS.TAB:
			case KEYS.BACKSPACE:
			case KEYS.LEFT:
			case KEYS.RIGHT:
			case KEYS.PERIOD:
			case KEYS.NUMPAD_DECIMAL:
			case KEYS.NUMPAD_SUBTRACT:
				return true;
				
			default:				
				if ((event.keyCode >= 96 && event.keyCode <= 105) || // numeric keypad 0-9
					(new RegExp('[' + this._validChars() + ']', 'i').test(String.fromCharCode(event.keyCode)))) {
					return true;
				};
		}
		
		return false;
	},
	_mousewheel: function() {
		var self = this;
		if ($.fn.mousewheel && self.options.mouseWheel) {
			this.element.mousewheel(function(event, delta) {
				delta = ($.browser.opera ? -delta / Math.abs(delta) : delta);
				self._start(event);
				self._spin((delta > 0 ? 1 : -1) * self.options.step, event);
				if (self.timeout) {
					window.clearTimeout(self.timeout);
				}
				self.timeout = window.setTimeout(function() {
					self._stop(event);
					self._change(event);
				}, 400);
				event.preventDefault();			
			});			
		}
	},
	_value: function(newVal) {
		if (!arguments.length) {
			return this._parse(this.element.val());
		}
		
		this._format(newVal);
		this.uiSpinner && this.uiSpinner.attr('aria-valuenow', this.value());
	},
	_setData: function(key, value) {
		$.widget.prototype._setData.call(this, key, value);
		
		if (key === 'buttons') {
			this._hide();
			return;
		}		

		if (/min|max|step/.test(key) && this.options[key] != null) {
			// write attributes back to element if original exist
			if (this.element.attr(key)) {
				this.element.attr(key, value);
			}
			
			// add aria properties
			if (/min|max/.test(key)) {
				this.element.parent().attr('aria-value'+key, value);
			}

			return;
		}
	},
	_validChars: function() {
		var radix = parseInt(this.options.radix);
		return '\\-\\' + this.options.radixPoint + (this.options.groupSeparator
			? '\\' + this.options.groupSeparator
			:'') + (radix < 10
				? '0-' + radix
				: '0-9' + (radix > 10
					? 'a-' + String.fromCharCode('a'.charCodeAt(0) + radix - 11)
					:''));
	},
	_parse: function(val) {
		if (typeof val == 'string') {
			if (this.options.groupSeparator) {
				val = val.replace(new RegExp('\\'+this.options.groupSeparator,'g'), '');
			}
			val = val.replace(new RegExp('[^' + this._validChars() + ']', 'gi'), '').split(this.options.radixPoint);
			result = parseInt(val[0], this.options.radix);
			if (val.length > 1) {
				result += parseInt(val[1], this.options.radix) / Math.pow(this.options.radix, val[1].length) *
					// must test first character of val[0] for minus sign because -0 is parsed as 0 in result
					(val[0][0] == '-' ? -1 : 1);
			}
			val = result;			
		}
		return isNaN(val) ? null : val;
	},
	_format: function(num) {
		var regex = /(\d+)(\d{3})/,
			options = this.options,
			sym = options.currency || '',
			dec = this._precision,
			radix = options.radix,
			group = options.groupSeparator,
			pt = options.radixPoint;
			
		for (
			num = (
				isNaN(num)
					? options.value
					: radix === 10
						? parseFloat(num, radix).toFixed(dec) 
						: parseInt(num, radix)
				).toString(radix).replace('.', pt);
			regex.test(num) && group;
			num = num.replace(regex, '$1'+group+'$2')
		);

		result = num.replace('-','');
		while (options.padding && (result.length < options.padding)) {
			result = '0' + result;
		}
		
		this.element.val((num < 0 ? '-' : '') + sym + result);
	},
	_hide: function(hide) {
		if (this.options.buttons === buttonDefault) {
			this.buttons.show();
			return;
		}
		
		if  (this.options.buttons === 'hide') {
			this.buttons.hide();
			return;
		}

		var self = this,
			speed = this.options.buttons === 'auto' ? 400 : this.options.buttons;
			
		if (this.timeout) {

			window.clearTimeout(this.timeout);
		}
		this.timeout = window.setTimeout(function() {
			self.buttons.animate({opacity: hide ? 'hide' : 'show'}, speed);
		}, 200);
	},
	
	destroy: function() {
		if ($.fn.mousewheel) {
			this.element.unmousewheel();
		}
		
		this.element
			.removeClass('ui-spinner-box')
			.removeAttr('disabled')
			.removeAttr('autocomplete')
			.removeData('spinner')
			.unbind(namespace);
			
		if (this.uiSpinner) {
			this.uiSpinner.replaceWith(this.element);	
		}
	},
	enable: function() {
		this.element
			.removeAttr('disabled')
			.siblings()
				.removeAttr('disabled')
			.parent()
				.removeClass('ui-spinner-disabled ui-state-disabled');
		this.options.disabled = false;
	},
	disable: function() {
		this.element
			.attr('disabled', true)
			.siblings()
				.attr('disabled', true)
			.parent()
				.addClass('ui-spinner-disabled ui-state-disabled');
		this.options.disabled = true;
	},
	value: function(newVal) {
		if (!arguments.length) {
			return this._value();
		}
		
		this._value(newVal);
	},
	stepUp: function(steps) {
		this._spin((steps || 1) * this.options.step, null);
		return this;
	},
	stepDown: function(steps) {
		this._spin((steps || 1) * -this.options.step, null);	
		return this;
	},
	pageUp: function(pages) {
		return this.stepUp((pages || 1) * this.options.page);		
	},
	pageDown: function(pages) {
		return this.stepDown((pages || 1) * this.options.page);		
	}
});

$.extend($.ui.spinner, {
	version: "@VERSION",
	eventPrefix: "spin",
	defaults: {
		buttons: buttonDefault,
		currency: false,
		dir: 'ltr',
		groupSeparator: '',
		incremental: true,
		max: null,
		min: null,
		mouseWheel: true,
		padding: 0,
		page: 5,
		precision: 0,
		radix: 10,
		radixPoint: '.',
		readOnly: null,
		spinnerClass: null,
		step: null,
		value: 0,
		width: false
	}
});

})(jQuery);
// JavaScript Document