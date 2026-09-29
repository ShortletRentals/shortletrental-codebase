import createDataContext from "./createDataContext";

// import { navigate } from "../navigationRef";
// import * as Facebook from "expo-facebook";
// import firebase from "firebase";
import axios from "axios";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { AddToCart, add_To_Favourite, Default_Card, Delete_Card, Forgot_Password, get_store_card, Login_Url, Logout, Send_Otp, Sign_UP, Update_Card, Verify_Otp } from "../network/Webconstant";
import { showMessage } from "react-native-flash-message";
import { useRoute } from "@react-navigation/native";
import { Toast } from "react-native-toast-message/lib/src/Toast";

const cardReducer = (state, action) => {
    switch (action.type) {
        case "updateCardData":
            return { ...state, cardData: action.payload.cardData, activityIndicator: false };
        case "UPDATE_SIGNUP_PARAMETER":
            return { ...state, Mobile: action.payload.Mobile, Password: action.payload.Password, Otp: action.payload.Otp }
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
        console.log('====local', Localtoken, localuserID)

        if (Localtoken != null && Localtoken != "false") {
            dispatch({ type: "UPDATE_USER_ID", payload: { token: Localtoken, user_id: localuserID } });
            //   navigate("mainFlow");
        } else {
            //   navigate("loginFlow");
        }
    } catch (error) {
        console.log(error);
    }
};



const addNewCardApi = (dispatch) => async (data, headers, callback = () => { }) => {
    dispatch({ type: "loadActivityIndicator" })
    try {
        axios({
            method: 'post',
            url: AddToCart,
            data,
            headers
        }).then(
            function (response) {
                console.log('resssss add to cart', response.data)
                if (response.data.status == true) {
                    alert(response.data.message)
                    Toast.show({
                        type:'success',
                        text1:'Shortlet',
                        text2:response.data.message
                    })
                    callback()

                } else {

                    alert(response.data.message.year) ||
                        alert(response.data.message.cvv) ||
                        alert(response.data.message.card_holder_name) ||
                        alert(response.data.message.card_number) ||
                        alert(response.data.message.month)
                    dispatch({ type: "loadActivityIndicator" })
                }
            }
        ).catch((e) => {
            console.log('error is add to cart ', e);
            dispatch({ type: "loadActivityIndicator" })
        })
    } catch (error) {
        console.log('error', error)
        dispatch({ type: "loadActivityIndicator" })
    }
}

const getNewCardApi = (dispatch) => async (data, headers, callback = () => { }) => {
    try {
        dispatch({ type: "loadActivityIndicator" });
        axios({
            method: 'get',
            url: get_store_card,
            data,
            headers
        }).then(
            function (response) {
                console.log('resssss', response.data.data)
                if (response.data.status == true) {
                    dispatch({ type: 'updateCardData', payload: { cardData: response.data.data } })
                    callback()
                } else {
                    alert(response.data.message)
                    dispatch({ type: "loadActivityIndicator" });
                }
            }
        ).catch((e) => {
            console.log('error is ', e);
            dispatch({ type: "loadActivityIndicator" });

        })
    } catch (error) {
        console.log('something went wrong', error)
        dispatch({ type: "loadActivityIndicator" });
    }
}

const defaultCardApi = (dispatch) => async (data, headers, callback = () => { }) => {
    try {
        dispatch({ type: "loadActivityIndicator" });
      await  axios({
            method: 'post',
            url: Default_Card,
            data,
            headers
        }).then(
            function (response) {
                console.log('resssss', response.data)
                if (response.data.status == true) {
                    // alert(response.data.message)
                    Toast.show({
                        type: 'success',
                        text1: 'Shortlet',
                        text2: response.data.message
                    })
                    callback()
                } else {
                    // alert(response.data.message ||response.data.message.card_id)
                    Toast.show({
                        type: 'error',
                        text1: 'Shortlet',
                        text2: response.data.message.card_id || response.data.message
                    })
                    dispatch({ type: "loadActivityIndicator" });
                }
            }
        ).catch((e) => {
            console.log('error is ', e);
            dispatch({ type: "loadActivityIndicator" });

        })
    } catch (error) {
        console.log('something went wrong', error)
        dispatch({ type: "loadActivityIndicator" });
    }
}

const editCardApi = (dispatch) => async (data, headers, callback = () => { }) => {
    try {
        axios({
            method: 'post',
            url: Update_Card,
            data,
            headers
        }).then(
            function (response) {
                console.log('update card resssss', response.data)
                if (response.data.status == true) {
                    alert(response.data.message)
                    callback()
                } else {
                    alert(response.data.message.card_holder_name || response.data.message.card_number || response.data.message.cvv || response.data.message.month || response.data.message.year || response.data.message || response.data.message.card_id)
                }
            }
        ).catch((e) => {
            console.log('error is ', e);
        })
    } catch (error) {
        console.log('error', error)
    }
}
const deleteCardApi = (dispatch) => async (data, headers, callback = () => { }) => {
    try {
        axios({
            method: 'post',
            url: Delete_Card,
            data,
            headers
        }).then(
            function (response) {
                console.log('delete card', response.data)
                if (response.data.status == true) {
                    Toast.show({
                        type: 'success',
                        text1: 'Shortlet',
                        text2: response.data.message
                    })
                    callback()
                } else {
                    Toast.show({
                        type: 'error',
                        text1: 'Shortlet',
                        text2: response.data.message.card_id || response.data.message
                    })
                    // alert(response.data.message.card_id || response.data.message)
                }
            }
        ).catch((e) => {
            console.log('error is ', e);
        })
    } catch (error) {
        console.log('error', error)
    }
}


export const { Provider, Context } = createDataContext(
    cardReducer,
    {

        addNewCardApi,
        getNewCardApi,
        defaultCardApi,
        editCardApi,
        deleteCardApi
    },
    { USER_ID: null, errorMessage: "", activityIndicator: false, cardData: [] }
);