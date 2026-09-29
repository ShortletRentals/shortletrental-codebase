import React from "react";
import { ValidationRegex } from "./ValidationRegex";

export function ValidationAction(valType,value, extra){
    if(valType=='confirmPassword' && (value!=extra)){
        return ValidationRegex[valType].error;
    }
    else if(valType=='confirmPassword' && value==extra && value!=''){
        return ''
    }
    else if(value=='' || value==null){
        return ValidationRegex[valType].emptyError
    }
    else if(value.match(ValidationRegex[valType].regex)){
        return ''
    }
    else{
        return ValidationRegex[valType].error;
    }
}