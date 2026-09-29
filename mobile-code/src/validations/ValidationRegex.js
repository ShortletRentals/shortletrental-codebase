export var ValidationRegex = {
  email: {
    regex: /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
    error: 'validMailError',
    emptyError: 'emailError',
  },

  mobileno: {
    regex: /^[0-9]{8,13}$/,
    error: 'validMobile',
    emptyError:'emptyMobile',
  },

  password: {
    regex: /^(?=(?:[^A-Z]*[A-Z]){1})(?=(?:[^0-9]*[0-9]){1}).{6,15}$/,
    error: 'passwordLength',
    emptyError: 'emptyPassword',
  },

  confirmPassword:{
    error: 'confirmPasswordErr',
    emptyError:'emptyConfirmPass',
  },

  name:{
    regex:/^[A-Za-z\s]{1,}[\.]{0,1}[A-Za-z\s]{2,15}$/,
    error: 'validNameError',
    emptyError: 'nameError',
  },

  lastName:{
    regex:/^[A-Za-z\s]{1,}[\.]{0,1}[A-Za-z\s]{0,15}$/,
    error: 'validNameError',
    emptyError: 'nameError',
  },
  
  paymentNumber:{
    regex: /^[0-9]{16}$/,
    error: 'paymentValidationError',
    emptyError:'emptyPaymentError',
  },

  expireDate:{
    regex:/^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$/,
    error: 'validExpireDate',
    emptyError: 'emptyExpireDate',
  },

  cvvNumber:{
    regex: /^[0-9]{3,4}$/,
    error: 'validCVVNumber',
    emptyError:'emptyCVVNumber',
  },
};