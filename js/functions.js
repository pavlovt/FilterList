utl = {
	"empty": function (mixed_var) {
	  // Checks if the argument variable is empty
	  // undefined, null, false, number 0, empty string,
	  // string "0", objects without properties and empty arrays
	  // are considered empty
	  //
	  // http://kevin.vanzonneveld.net

	  var undef, key, i, len;
	  var emptyValues = [undef, null, false, 0, "", "0"];

	  for (i = 0, len = emptyValues.length; i < len; i++) {
	    if (mixed_var === emptyValues[i]) {
	      return true;
	    }
	  }

	  if (typeof mixed_var === "object") {
	    for (key in mixed_var) {
	      // TODO: should we check for own properties only?
	      //if (mixed_var.hasOwnProperty(key)) {
	      return false;
	      //}
	    }
	    return true;
	  }

	  return false;
	}
}