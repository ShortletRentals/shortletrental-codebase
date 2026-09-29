import createDataContext from './createDataContext';

// import { navigate } from "../navigationRef";
// import * as Facebook from "expo-facebook";
// import firebase from "firebase";
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import {
  add_To_Favourite,
  Change_Password,
  Forgot_Password,
  Login_Url,
  Logout,
  Send_Otp,
  Sign_UP,
  Verify_Otp,
} from '../network/Webconstant';
import { showMessage } from 'react-native-flash-message';
import { useRoute } from '@react-navigation/native';
import { Toast } from 'react-native-toast-message/lib/src/Toast';

const authReducer = (state, action) => {
  switch (action.type) {
    case 'UPDATE_USER_ID':
      return {
        ...state,
        USER_ID: action.payload.user_id,
        Token_ID: action.payload.token,
      };
    case 'UPDATE_USER_DATA':
      return { ...state, USERDATA: action.payload.USERDATA };
    case 'UPDATE_SIGNUP_PARAMETER':
      return {
        ...state,
        Mobile: action.payload.Mobile,
        Password: action.payload.Password,
        Otp: action.payload.Otp,
      };
    case 'loadActivityIndicator':
      return { ...state, activityIndicator: !state.activityIndicator };
    case 'AUTH_FAILED':
      return {
        ...state,
        errorMessage: action.payload,
        activityIndicator: false,
      };
    case 'SIGN_OUT_USER':
      return { ...state, user_token: '', activityIndicator: false };
    default:
      return state;
  }
};

//optional function to check active user
const checkActiveUser = dispatch => async () => {
  try {
    const Localtoken = await AsyncStorage.getItem('token_id');
    const localuserID = await AsyncStorage.getItem('USER_ID');
    // const localUserData = await AsyncStorage.getItem("User_Data")
    console.log('====local', Localtoken, localuserID);

    if (Localtoken != null && Localtoken != 'false') {
      dispatch({
        type: 'UPDATE_USER_ID',
        payload: { token: Localtoken, user_id: localuserID },
      });
      return true;
      // dispatch({type:'UPDATE_USER_DATA',payload:{user_data:localUserData}})
      //   navigate("mainFlow");
    } else {
      return false;
      //   navigate("loginFlow");
    }
  } catch (error) {
    console.log(error);
  }
};

const signInUser =
  dispatch =>
    async (data, callback = () => { }) => {
      try {
        // dispatch({ type: "loadActivityIndicator" });
        //
        axios({
          method: 'post',
          url: Login_Url,
          data,
          // headers: { "content-type": "application/x-www-form-urlencoded" }
        })
          .then(async function (response) {
            // console.log("API Response Login -----", response.data);

            if (response.data.status === true) {
              await AsyncStorage.setItem('token_id', response.data.token);
              await AsyncStorage.setItem(
                'USER_ID',
                JSON.stringify(response.data.data.id),
              );
              await AsyncStorage.setItem(
                'User_Data',
                JSON.stringify(response.data.data),
              );
              dispatch({
                type: 'UPDATE_USER_ID',
                payload: {
                  USER_ID: JSON.stringify(response.data.data.id),
                  Token_ID: response.data.token,
                },
              });
              dispatch({
                type: 'UPDATE_USER_DATA',
                payload: { USERDATA: response.data.data },
              });
              // console.log('idddddd', response.data.data.id)
              // dispatch({ type: "loadActivityIndicator" });

              callback();
              Toast.show({
                type: 'success',
                text1: 'Shortlet',
                text2: response.data.message,
              });
            } else {
              Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: response.data.message,
              });
              // dispatch({ type: "loadActivityIndicator" });
              // showMessage({
              //   message: response.data.message,
              //   type: "danger",
              // });
            }
          })
          .catch(error =>
            console.log('error login', error)
          );
        // dispatch({ type: "loadActivityIndicator" });
      } catch (error) {
        console.log('error logins', error);
        // dispatch({ type: "loadActivityIndicator" });
      }
    };
const becomeAHostSignin =
  dispatch =>
    async (data, callback = () => { }) => {
      try {
        dispatch({ type: 'loadActivityIndicator' });
        //
        axios({
          method: 'post',
          url: 'https://zea-virtual-events.com/shortletrental/api/auth/becomeahost_signUp',
          data,
          // headers: { "content-type": "application/x-www-form-urlencoded" }
        })
          .then(async function (response) {
            // console.log("API Response Login -----", response.data);

            if (response.data.status === true) {
              await AsyncStorage.setItem('token_id', response.data.token);
              await AsyncStorage.setItem(
                'USER_ID',
                JSON.stringify(response.data.data.id),
              );
              await AsyncStorage.setItem(
                'User_Data',
                JSON.stringify(response.data.data),
              );
              dispatch({
                type: 'UPDATE_USER_ID',
                payload: {
                  USER_ID: JSON.stringify(response.data.data.id),
                  Token_ID: response.data.token,
                },
              });
              dispatch({
                type: 'UPDATE_USER_DATA',
                payload: { USERDATA: response.data.data },
              });
              // console.log('idddddd', response.data.data);
              dispatch({ type: 'loadActivityIndicator' });
              alert('hhhhh');

              callback();
              Toast.show({
                type: 'success',
                text1: 'Shortlet',
                text2: response.data.message,
              });
              props.navigation.navigate('Otp');
            } else {
              Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: response.data.message,
              });
              alert('false');

              dispatch({ type: 'loadActivityIndicator' });
              // showMessage({
              //   message: response.data.message,
              //   type: "danger",
              // });
            }
          })
          .catch(error => console.log(error));
        dispatch({ type: 'loadActivityIndicator' });
      } catch (error) {
        console.log(error);
        dispatch({ type: 'loadActivityIndicator' });
      }
    };

const signUpUser =
  dispatch =>
    async (data, callback = () => { }) => {
      try {
        dispatch({ type: 'loadActivityIndicator' });
        axios({
          method: 'post',
          url: Sign_UP,
          data,
        })
          .then(function (response) {
            console.log("API Response -----", response.data);
            if (response.data.status === 1) {
              dispatch({
                type: 'UPDATE_SIGNUP_PARAMETER',
                payload: {
                  Mobile: response.data.data.mobile,
                  Otp: response.data.data.otp,
                },
              });
              // props.navigation.navigate('Otp', { paramsotp: { mobile: mobileNumber, country_code: '+91', pass: password },from:"SignUp" })
              // if (response.data.status === true){
              //  if( sendOtpHandler({type:'register',country_code:'+91',mobile:response.data.request.mobile})=== true ){
              Toast.show({
                type: 'success',
                text1: 'Shortlet',
                text2: response.data.message,
              });
              callback();
            }
            // }
            else {
              Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: response.data.message && response.data.message.email,
              });
              // alert(response.data.message.email || response.data.message)
            }
          })
          .catch(error => console.log(error));
      } catch (error) {
        dispatch({ type: 'loadActivityIndicator' });
        console.log('error is', error);
      }
    };

// const route = useRoute()

const verifyOtpHandler =
  dispatch =>
    async (data, callback = () => { }) => {
      try {
        // console.log("===data", data)
        dispatch({ type: 'loadActivityIndicator' });
        axios({
          method: 'post',
          url: Verify_Otp,
          data,
          // headers: { "content-type": "application/x-www-form-urlencoded" }
        })
          .then(function (response) {
            // console.log("API Response verify-----", response.data);
            if (response.data.status === true) {
              // navigation.navigate('Logins')
              callback();
              Toast.show({
                type: 'success',
                text1: 'Shortlet',
                text2: response.data.message,
              });
            } else {
              Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: response.data.message,
              });
            }
          })
          .catch(error => console.log(error));
      } catch (error) {
        dispatch({ type: 'loadActivityIndicator' });
        console.log('error is a', error);
      }
    };

const resendOtpHandler =
  dispatch =>
    (data, callback = () => { }) => {
      try {
        dispatch({ type: 'loadActivityIndicator' });
        axios({
          method: 'post',
          url: Send_Otp,
          data,
        })
          .then(function (response) {
            // console.log("API Response -----", response.data);

            if (response.data.status === true) {
              Toast.show({
                type: 'success',
                text1: 'Shortlet',
                text2: response.data.message,
              });
              callback();
            } else if (response.data.status == false) {
              Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: response.data.message,
              });
            }
          })
          .catch(error => console.log(error));
      } catch (error) {
        console.log('error is', error);
      }
    };

const logoutUser =
  dispatch =>
    (headers, callback = () => { }) => {
      try {
        axios({
          method: 'post',
          url: Logout,
          headers,
        })
          .then(function (response) {
            // console.log("API Response Logout -----", response.data);

            if (response.data.status === true) {
              Toast.show({
                type: 'success',
                text1: 'Shortlet',
                text2: response.data.message,
              });

              callback();
              // setUser('')
            } else {
              Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: response.data.message,
              });
            }
          })
          .catch(error => console.log('something errror is ', error));
      } catch (error) {
        console.log('eeer', error);
      }
    };

const forgotPassword =
  dispatch =>
    async (data, callback = () => { }) => {
      try {
        axios({
          method: 'post',
          url: Forgot_Password,
          data,
        })
          .then(function (response) {
            // console.log('ressssss forgot ', response.data)
            if (response.data.status === 1) {
              
              Toast.show({
                type: 'success',
                text1: 'Shortlet',
                text2: response.data.message,
              });

              callback();
            } else {
              Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2:
                  response.data.message.mobile ||
                  response.data.message.country_code ||
                  response.data.message,
              });
            }
          })
          .catch(error => {
            console.log('error', error);
          });
      } catch (error) {
        console.log('e', error);
      }
    };

const changePasswordApi =
  dispatch =>
    async (data, headers, callback = () => { }) => {
      try {
        axios({
          method: 'post',
          url: Change_Password,
          data,
          headers,
        })
          .then(function (response) {
            if (response.data.status === true) {
              Toast.show({
                type: 'success',
                text1: 'Shortlet',
                text2: response.data.message,
              });
              callback();
            } else {
              Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: response.data.message,
              });
            }
          })
          .catch(error => console.log('Error', error));
      } catch (e) {
        console.log('errr', e);
      }
    };

export const { Provider, Context } = createDataContext(
  authReducer,
  {
    signInUser,
    signUpUser,
    logoutUser,
    verifyOtpHandler,
    resendOtpHandler,
    forgotPassword,
    changePasswordApi,
    // homeApi,
    checkActiveUser,
    becomeAHostSignin,

    // signOutUser,
  },
  {
    USER_ID: null,
    errorMessage: '',
    activityIndicator: false,
    Mobile: '',
    Password: '',
    country_code: '+91',
    Otp: '',
    Token_ID: '',
    USERDATA: {},
  },
);
