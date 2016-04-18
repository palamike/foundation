function object_to_encoded_url(obj) {
    "use strict";
    var str = [];
    for( var p in obj ){
        if (obj.hasOwnProperty(p)) {
            str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
        }
    }
    return str.join("&");
};

function clone_object(obj) {
    "use strict";
    var copy;

    // Handle the 3 simple types, and null or undefined
    if (null === obj || "object" !== typeof obj) { return obj; }

    // Handle Date
    if (obj instanceof Date) {
        copy = new Date();
        copy.setTime(obj.getTime());
        return copy;
    }

    // Handle Array
    if (obj instanceof Array) {
        copy = [];
        for (var i = 0, len = obj.length; i < len; i++) {
            copy[i] = clone_object(obj[i]);
        }
        return copy;
    }

    // Handle Object
    if (obj instanceof Object) {
        copy = {};
        for (var attr in obj) {
            if (obj.hasOwnProperty(attr)) copy[attr] = clone_object(obj[attr]);
        }
        return copy;
    }

    throw new Error("Unable to copy obj! Its type isn't supported.");
};

function count_object_props(obj){
    "use strict";
    var count = 0;
    for (var prop in obj) {
        count++;
    }
    return count;
};