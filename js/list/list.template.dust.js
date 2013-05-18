List.prototype.templateEngines.dustjs = function(list, settings) {
    var h = ListJsHelpers, listSource = h.getByClass(settings.listClass, list.listContainer, true),
        itemSource = getItemSource(settings.item),
        templater = this;

    init();

    function getItemSource(item) {
        if (item === undefined) {
            var nodes = listSource.childNodes,
                items = [];

            for (var i = 0, il = nodes.length; i < il; i++) {
                // Only textnodes have a data attribute
                if (nodes[i].data === undefined) {
                    return nodes[i];
                }
            }
            return null;
        } else if (item.indexOf("<") !== -1) { // Try create html element of list, do not work for tables!!
            var div = document.createElement('div');
            div.innerHTML = item;
            return div.firstChild;
        } else {
            return document.getElementById(settings.item);
        }
    }

    var ensure = {
        created: function(item) {
            if (item.elm === undefined) {
                templater.create(item);
            }
        }
    };

    function init() {
        var c = dust.compile(jQuery(settings.template.element).html(), settings.template.name);
        dust.loadSource(c);
        //console.log("test!!!");
    }

    /* Get values from element */
    this.get = function(item, valueNames) {
        ensure.created(item);
        var values = {};
        for(var i = 0, il = valueNames.length; i < il; i++) {
            var elm = h.getByClass(valueNames[i], item.elm, true);
            values[valueNames[i]] = elm ? elm.innerHTML : "";
        }
        return values;
    };

    /* Sets values at element */
    this.set = function(item, values) {
        //console.log(item, values);
        ensure.created(item);
        dust.render(settings.template.name, values, function(err, out) {
          jQuery(item.elm).html(out);
        });
    };

    this.create = function(item) {
        if (item.elm !== undefined) {
            return;
        }
        /* If item source does not exists, use the first item in list as
        source for new items */
        var newItem = itemSource.cloneNode(true);
        newItem.id = "";
        item.elm = newItem;
        templater.set(item, item.values());
    };
    this.remove = function(item) {
        listSource.removeChild(item.elm);
    };
    this.show = function(item) {
        ensure.created(item);
        listSource.appendChild(item.elm);
    };
    this.hide = function(item) {
        if (item.elm !== undefined && item.elm.parentNode === listSource) {
            listSource.removeChild(item.elm);
        }
    };
    this.clear = function() {
        /* .innerHTML = ''; fucks up IE */
        if (listSource.hasChildNodes()) {
            while (listSource.childNodes.length >= 1)
            {
                listSource.removeChild(listSource.firstChild);
            }
        }
    };
};