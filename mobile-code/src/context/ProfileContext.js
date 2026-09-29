import createDataContext from "./createDataContext";

// import { navigate } from "../navigationRef";
// import * as Facebook from "expo-facebook";
// import firebase from "firebase";
import axios from "axios";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { add_To_Favourite, Forgot_Password, Login_Url, Logout, Royalty_Points, Send_Otp, Sign_UP, Update_Profile, User_Profile, Verify_Otp } from "../network/Webconstant";
import { showMessage } from "react-native-flash-message";
import { useRoute } from "@react-navigation/native";
import { Toast } from "react-native-toast-message/lib/src/Toast";

const profileReducer = (state, action) => {
  switch (action.type) {
    case "UPDATE_PROFILE_DATA":
      return { ...state, USER_ID: action.payload.user_id,  };
    case "UPDATE_USER_DATA":
      return { ...state, ProfileUserData: action.payload.ProfileUserData}
    case "UPDATE_ROYALTY_POINT":
      return { ...state, royaltyPoint: action.payload.royaltyPoint}
    case "loadActivityIndicator":
      return { ...state, activityIndicator: !state.activityIndicator };
    case "AUTH_FAILED":
      return {
        ...state,
        errorMessage: action.payload,
        activityIndicator: false,
      };
    case "SIGN_OUT_USER":
      return { ...state, user_token: "", activityIndicator: false };
    default:
      return state;
  }
};

//optional function to check active user
const checkActiveUser = (dispatch) => async () => {
  try {
    const Localtoken = await AsyncStorage.getItem("token_id");
    const localuserID = await AsyncStorage.getItem("USER_ID");
    console.log('====local',Localtoken,localuserID)

    if (Localtoken != null && Localtoken != "false") {
      dispatch({ type: "UPDATE_USER_ID", payload:{token:Localtoken,user_id:localuserID}  });
      //   navigate("mainFlow");
    } else {
      //   navigate("loginFlow");
    }
  } catch (error) {
    console.log(error);
  }
};

const editProfileApi = (dispatch)=>async(data,headers,callback = ()=>{})=>{
try {
  dispatch({type:'loadActivityIndicator'})
    axios({
        method:'post',
        url:Update_Profile,
        data,
        headers
    }).then(
        function(response){
          if(response.data.status == true){
              console.log('====update profiel res',response)
              Toast.show({
                type:'success',
                text1:'Shortlet',
                text2:response.data.message
              })
              callback()
              
            }else{
              Toast.show({
                type:'error',
                text1:'Shortlet',
                text2:response.data.message
              })
              dispatch({type:'loadActivityIndicator'})
            }
        }
    ).catch((e)=>{
        console.log('eeee',e);
        dispatch({type:'loadActivityIndicator'})
    })
} catch (error) {
    console.log('error',error);
    dispatch({type:'loadActivityIndicator'})
}
}

const getUserProfile = (dispatch)=>async(headers,callback=()=>{})=>{
  dispatch({type:'loadActivityIndicator'})
  try {
    axios({
      method:'get',
      url:User_Profile,
            headers
    }).then(
      function(res){
        if(res.data.status){
          console.log('user_profile',res.data.message)
          dispatch({type:'UPDATE_USER_DATA',payload:{ProfileUserData: res.data.data}})
          dispatch({type:'loadActivityIndicator'})
          // alert(res.data.message)
        }else{
          // alert(res.data.message)
          dispatch({type:'loadActivityIndicator'})
        }
      }
    ).catch((e)=>{
      console.log('error is',e)
      dispatch({type:'loadActivityIndicator'})
    })
  } catch (error) {
    console.log('something went wrong',error)
    dispatch({type:'loadActivityIndicator'})
  }
}

const royaltyPointsReedemApi = (dispatch)=>async(headers,callback=()=>{})=>{
 try {
  axios({
    method:'get',
    url:Royalty_Points,
    headers
  }).then(
    function(res){
      console.log('====res',res.data.data)
      if(res.data.status){
        dispatch({type:'UPDATE_ROYALTY_POINT',payload:{royaltyPoint: res.data.data}})
        // alert(res.data.message)
      }else{
        // alert(res.data.message)
      }
    }
  ).catch((e)=>{
    console.log('something went wrong',e)
  })
 } catch (error) {
  console.log('error is ',error)
 }
}

export const { Provider, Context } = createDataContext(
  profileReducer,
  {
   editProfileApi,
   getUserProfile,
   royaltyPointsReedemApi

  },
  { USER_ID: null, errorMessage: "", activityIndicator: false, ProfileUserData:{},royaltyPoint:[] }
);