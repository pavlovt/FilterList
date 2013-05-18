function filterList(o, $) {
    var self = this;

    function updateState() {

	    var chash = hash.get();
	    
	    if(!utl.empty(chash)) {
	    	$.each(chash, function(key, value){
	    		//console.log(key, value);
	    		$("[name=" + key + "]").val(value);
	    	});

	    	self.searchFilter();
	    	self.list.search();
	    	$('[name=search_all]').trigger('keyup');
	    }


    }
    
    function createList () {
        var pagingTop = {
              name: "pagingTop",
              pagingClass: o.pagingTop,
              outerWindow: 2
            };

            var pagingBottom = {
              name: "pagingBottom",
              pagingClass: o.pagingBottom,
              outerWindow: 2
            };
            
            var options = {
                  item: 'item',
                  page: 20,
                  template: o.template,
                  engine: o.engine,
                  plugins: [
                      [ 'paging', pagingTop],
                      [ 'paging', pagingBottom]
                  ]
            };

            // create the list and load the data
            self.list = new List(o.list, options, o.data);
            updateState();


    }

    function toString (data) {
        if (!!data) {
            return data.toString();
        }

        return "";
    }

    function isArray(arr) {
        if (!!!arr) {
            return false;
        }

        if (Array.isArray) {
          return Array.isArray(arr);

        } else if( Object.prototype.toString.call( arr ) === '[object Array]' ) {
          return true;
        }

        return false;
    }

    self.searchFilter = function (list, searchForm) {
      if (self.list) {
        //console.log($scope.search.level.val);
        var search = self.formToObj();
        //console.log(search);
        self.list.filter(function(item) {
            //console.log(item.values());
            item = item.values();
            var result = true;
            $.each(search, function(field, obj){
                if (obj.hasOwnProperty("val") && toString(obj.val) != "") {
                    //console.log(field, obj, item);
                    //return false;

                    switch(obj.operator) {
                        case "in":
                          if (isArray(obj.val) && obj.val.indexOf(item[field]) != -1) {
                            result *= true;
                            return true; //continue;
                          }
                        break;

                        /*case ">":
                          obj.val = parseInt(obj.val, 10);
                          if (angular.isNumber(obj.val) && obj.val > item[field]) {
                            result *= true;
                            return true;
                          }
                        break;*/

                        case "=":
                        default:
                          if (toString(obj.val) == toString(item[field])) {
                            result *= true;
                            return true;
                          }
                        break;
                    }

                    result = false;
                    return false; // break
                }
            });

            return result;
        });
    }

    };

    // get an object from the form's inputs
    self.formToObj = function () {
        var o = {};
        var a = $("#" + self.o.searchForm).serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name].val = [o[this.name].val];
                    o[this.name].operator = "in";
                }
                o[this.name].val.push(this.value || '');
            } else {
                o[this.name] = {};
                o[this.name].val = this.value || '';
                // default operator (if none is given) is =
                o[this.name].operator = $("[name=" + this.name + "]").attr("data-operator") || '=';
                o[this.name].type = $("[name=" + this.name + "]").attr("data-type") || '';
            }
            /*console.log(this, $(this).attr("operator"));
            o[this.name] = {};
            o[this.name].val = this.value || '';*/
            
        });
        return o;
    };

    function init() {
	    if (!!!o) {
	        o = {list: "filterList", pagingTop: "pagingTop", pagingBottom: "pagingBottom", searchForm: "search_form", template: {name: "template", element: "#myelm"}, data: [], url: ""};
	    }

	    self.o = o;

	    if (!!o.data || o.data.length == 0) {
	        $.getJSON(o.url, function(data) {  
	            o.data = data;
	            createList();
	    		return self;
	        });
	        
	        
	    } else {
	        createList();
	        return self;

	    }

	    return self;


    }

    init();
    
};