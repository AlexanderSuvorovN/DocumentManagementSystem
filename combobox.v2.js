function Combobox(node, options = {})
{
	this.node = node;
	this.input_node = this.node.find("input[type='text']");
	this.button_node = $("<div></div>").addClass("button").appendTo(this.node);
	this.height = this.node.outerHeight();
	this.list_offset_top = 2;
	this.list_offset_bottom = -2;
	this.list_margin = 16;
	this.list = {};
	this.list.node = $("<div></div>").addClass("list").hide().appendTo(this.node);
	this.list.items = null;
	this.list.current_hover_item = null;
	this.list.collapse = 
		function ()
		{
			this.node.empty().hide();
			this.current_hover_item = null;
		}
	this.set_value("");
	if(options.ajax_url !== undefined)
	{
		this.ajax_url = options.ajax_url;
	}
	if(options.select_callback !== undefined)
	{
		this.select_callback = options.select_callback;
		$.proxy(this.select_callback, this);
	}
	this.allow_new_items = (options.allow_new_items !== undefined) ? options.allow_new_items : false;
	this.on_input_enter = (options.on_input_enter !== undefined) ? options.on_input_enter : null;
	let self = this;
	function get_bounding_rect(node)
	{
		let rect = {};
		let offset = node.offset();
		rect.x = offset.left;
		rect.y = offset.top - $(window).scrollTop();
		rect.width = node.outerWidth();
		rect.height = node.outerHeight();
	    for(k of Object.keys(rect))
		{
			rect[k] = Math.round(rect[k]);
		}
		return rect;
	}
	function get_item_by_mouse_pointer(mp)
	{
		let return_index = null;
		// let listRect = self.list.node[0].getBoundingClientRect();
		let listRect = get_bounding_rect(self.list.node);
		// console.log(listRect);
		self.list.items.some(
			function(item, index)
			{
				// let itemRect = item.node[0].getBoundingClientRect();
				let itemRect = get_bounding_rect(item.node);
				if((itemRect.x + itemRect.width) >= listRect.x && (itemRect.y + itemRect.height) >= listRect.y && itemRect.x <= (listRect.x + listRect.width) && itemRect.y <= (listRect.y + listRect.height))
				{
					if(mp.x >= itemRect.x && mp.y >= itemRect.y && mp.x <= (itemRect.x + itemRect.width) && mp.y <= (itemRect.y + itemRect.height))
					{
						return_index = index;
						return true;
					}
				}
				else
				{
					// console.log("[" + index + "].'" + item.node.text() + "' is skipped since is not visible due to overflow");
				}
				// console.log(itemRect);
			});
		return return_index;
	}
	this.list.node.on(
		"mousemove",
		function(e)
		{
			let mp = {};
			mp.x = e.originalEvent.x;
			mp.y = e.originalEvent.y;
			// console.log("mp = (" + mp.x + "," + mp.y + ")");
			let index = get_item_by_mouse_pointer(mp);
			if(index !== null)
			{
				self._undo_hover();
				self.list.current_hover_item = index;
				self._do_hover();
			}
		});
	this.list.node.on(
		"click",
		function(e)
		{
			let mp = {};
			mp.x = e.originalEvent.x;
			mp.y = e.originalEvent.y;
			let index = get_item_by_mouse_pointer(mp);
			// console.log("mp = (" + mp.x + "," + mp.y + ")");
			// console.log(e);
			if(index !== null)
			{
				/*
				self._do_hover();
				*/
				self.list.current_hover_item = index;
				self._set_value_by_item();
				// let dot = $("<div></div>").css({position: "absolute", top: mp.y + $(window).scrollTop(), left: mp.x, backgroundColor: "red", width: "4px", height: "4px", zIndex: 11000}).appendTo("body");
				// console.log(self.list.items[index].node.text());
			}
		});
	this.button_node.on(
		"click", 
		function(e)
		{
			console.log("combobox.js > combobox button click");
			if(self.list.node.is(":visible"))
			{
				self.collapse();
			}
			else
			{
				self.expand({search: false});
				self.input_node.focus();
			}
		});
	this.input_node.on(
		"input",
		function(e)
		{
			console.log("combobox.js > input_node 'input' event");
			self.value = self.input_node.val().trim();
			self.node.data("value", self.value);
			if(self.list.node.is(":visible"))
			{
				console.log("combobox.js > refresh");
				self._ajax();
			}
			else
			{
				if(self.allow_new_items)
				{
					if(self.select_callback)
					{
						self.select_callback();
					}
				}
				else
				{
					self.expand();
				}
			}
		});
	this.input_node.on(
		"keydown",
		function(e)
		{
			if(e.originalEvent.key === "ArrowDown")
			{
				console.log("combobox.js > input_node 'keydown' event ('ArrowDown')");
				if(self.list.node.is(":visible"))
				{
					if(self.list.current_hover_item !== null)
					{
						if(self.list.current_hover_item < self.list.items.length - 1)
						{
							self._undo_hover();
							self.list.current_hover_item++;
							self._do_hover();
						}
					}
					else
					{
						self.list.current_hover_item = 0;
						self._do_hover();
					}
				}
				else
				{
					self.expand();
				}
			}
			if(e.originalEvent.key === "ArrowUp")
			{
				console.log("combobox.js > input_node 'keydown' event ('ArrowUp')");
				if(self.list.node.is(":visible"))
				{
					if(self.list.current_hover_item > 0)
					{
						self._undo_hover();
						self.list.current_hover_item--;
						self._do_hover();
					}
					else
					{
						// self.collapse();
					}
				}
			}
			if(e.originalEvent.key === "Enter")
			{
				if(self.list.node.is(":visible"))
				{
					if(self.list.current_hover_item !== null)
					{
						self._set_value_by_item();
					}
					else
					{
						let value_in_list = self._check_value_in_list();
						if(value_in_list !== null)
						{
							self.set_value(value_in_list.value);
							self.collapse();
						}
						self.on_input_enter && self.on_input_enter();
					}
				}
				else
				{
					self.on_input_enter && self.on_input_enter();
				}
			}
			if(e.originalEvent.key === "Escape")
			{
				if(self.list.node.is(":visible"))
				{
					self.collapse();
				}
			}
		});
	this.input_node.on(
		"blur", 
		function (e)
		{
			console.log("combobox.js > input_node 'blur' event");
			// console.log(e);
			if(self.list.node.is(":visible"))
			{
				let n = e.originalEvent.explicitOriginalTarget;
				if(n !== self.button_node[0] && $(n).closest("div.list").eq(0)[0] !== self.list.node[0])
				{
					self.collapse();
				}
			}
			if(!self.allow_new_items)
			{
				let value_in_list = self._check_value_in_list();
				if(value_in_list !== null)
				{
					self.set_value(value_in_list.value);
				}
				else
				{
					self.set_value("");
				}
			}
		});
	if(options.ajax_done_callback !== undefined)
	{
		this.ajax_done_callback = options.ajax_done_callback;
	}
	else
	{
		this.ajax_done_callback = this._ajax_done_callback;
	}
	$.proxy(this.ajax_done_callback, this);
}
Combobox.prototype._do_hover = function()
{
	let item_node = this.list.items[this.list.current_hover_item].node;
	let item_text = item_node.text().trim();
	let item_height = item_node.outerHeight();
	let item_top = item_node.position().top;
	let item_bottom = item_top + item_height;
	item_node.addClass("hover");
	// console.log("this.list.node.innerHeight(): " + this.list.node.innerHeight());
	// console.log("item_node.position().top: " + item_node.position().top);
	// console.log("this.list.node.scrollTop(): " + this.list.node.scrollTop());
	if(item_top < 0)
	{
		let scroll = item_top;
		this.list.node.scrollTop(this.list.node.scrollTop() + scroll);
	}
	else if(item_bottom > this.list.node.innerHeight())
	{
		let scroll = item_bottom - this.list.node.innerHeight();
		this.list.node.scrollTop(this.list.node.scrollTop() + scroll);
	}
}
Combobox.prototype._undo_hover = function()
{
	(this.list.current_hover_item !== null) && this.list.items[this.list.current_hover_item].node.removeClass("hover");
}
Combobox.prototype._set_value_by_item = function()
{
	let item_text = this.list.items[this.list.current_hover_item].node.text().trim();
	this.set_value(item_text);
	if(this.select_callback)
	{
		this.select_callback();
	}
	this.collapse();
}
Combobox.prototype._ajax = function(options = {})
{
	console.log("combobox.js > _ajax");
	let combobox = this;
	$.get(this.ajax_url, {search: (options.search !== false) ? this.value : ""}, null, "json")
		.done(
			function(data)
			{
				combobox.ajax_done_callback({data: data});
			});
}
Combobox.prototype._ajax_done_callback = function(options = {})
{
	console.log("combobox.js > _ajax_done_callback");
	let combobox = this;
	if(options.data !== undefined)
	{
		let data = options.data;
		combobox.list.node.empty();
		combobox.list.items = [];
		combobox.list.current_hover_item = null;
		for(const item_text of data)
		{
			let item = {};
			item.node = $("<div></div>").addClass("item").text(item_text);
			// item.node.on("click", (e) => combobox._set_value_by_item());
			combobox.list.items.push(item);
		}
		if(combobox.list.items.length)
		{
			combobox.list.node.show();
			let list_node_height = combobox.list.node.height();
			combobox.list.items.forEach(
				function(item)
				{
					item.node.appendTo(combobox.list.node);
				});
			let combobox_top = combobox.node.offset().top - $(window).scrollTop();
			let space_top = combobox_top - combobox.list_offset_top - combobox.list_margin;
			let space_bottom = $(window).height() - combobox_top - combobox.height - combobox.list_offset_bottom - combobox.list_margin;
			let min_height = list_node_height + (combobox.list.node.find("div.item").eq(0).outerHeight() * Math.min(combobox.list.items.length, 4));
			// console.log("space_top: " + space_top);
			// console.log("space_bottom: " + space_bottom);
			// console.log("list_node_height: " + list_node_height);
			// console.log("min_height: " + min_height);
			if(space_bottom >= min_height)
			{
				combobox.list.node.css("top", combobox.height + combobox.list_offset_bottom).css("max-height", space_bottom);
			}
			else
			{
				combobox.list.node.css("max-height", space_top);
				combobox.list.node.css("top", -combobox.list.node.outerHeight() - combobox.list_offset_top);
			}
		}
	}
}
Combobox.prototype.expand = function(options = {}) 
{
	this.list.node.is(":visible") && this.collapse();
	this.node.addClass("expanded");
	if(options.search !== undefined)
	{
		this._ajax({search: options.search});
	}
	else
	{
		this._ajax();
	}
};
Combobox.prototype.collapse = function()
{
	this.node.removeClass("expanded");
	this.list.collapse();
};
Combobox.prototype.clear_input = function()
{
	this.set_value("");
}
Combobox.prototype.set_value = function(value)
{
	this.value = value;
	this.node.data("value", this.value);
	this.input_node.val(this.value);
}
Combobox.prototype._check_value_in_list = function()
{
	let value_in_list = {};
	let found = false;
	let combobox = this;
	this.list.items && this.list.items.some(
		function(item, index)
		{
			if(item.node.text().toLowerCase() === combobox.value.toLowerCase())
			{
				value_in_list.value = item.node.text();
				value_in_list.index = index;
				found = true;
				return true;
			}
		});
	return ((found) ? value_in_list : null);
}