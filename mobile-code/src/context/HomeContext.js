import createDataContext from "./createDataContext";


import axios from "axios";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { add_To_Favourite, Forgot_Password, getAllCategory, getAllServicesApiUse, Home_Details, HOME_URL, Login_Url, Logout, my_favorites, Send_Otp, Sign_UP, Verify_Otp } from "../network/Webconstant";
import { ToastAndroid } from "react-native";
import { useState } from "react";
import { useLoading } from "./LoadingContext";
import { Toast } from "react-native-toast-message/lib/src/Toast";

const homeReducer = (state, action) => {
    switch (action.type) {
        case "updateHomeData":
            return { ...state, data: action.payload.data, };
        case "updateHOMEDATA":
            return { ...state, homeData: action.payload.homeData, };
        case "updateHomeProperty":
            return { ...state, homeProperty: action.payload.homeProperty, };
        case "updateHomeDetails":
            return { ...state, details: action.payload.details, }
        case "updateHomeDetailsData":
            return { ...state, homeDetailsData: action.payload.homeDetailsData, }
        case "updateHomeDetailsExtraServices":
            return { ...state, homeDetailsExtraServices: action.payload.homeDetailsExtraServices, }
        case "updateFavourite":
            return { ...state, homeDetails: action.payload.homeDetails, }
        case "updateAllCategory":
            return { ...state, category: action.payload.category, }
        case "updateAllServices":
            return { ...state, services: action.payload.services, }
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








const addToFavourite = (dispatch) => async (data, headers, callback = () => { }) => {
    try {
        // dispatch({ type: "loadActivityIndicator" });
        axios({
            method: 'post',
            url: add_To_Favourite,
            data,
            headers
        }).then(
            function (response) {
                console.log('adddddddtoddd', response.data);
                if (response.data.status == true) {
                    // ToastAndroid.show(response.data.message, ToastAndroid.LONG)
                    Toast.show({
                        type: 'error',
                        text1:'ShortletRental',
                        text2: response.data.message
                    })
                    callback()
                } else {
                    Toast.show({
                        type: 'error',
                        text1:'ShortletRental',
                        text2: response.data.message
                    })
                    // ToastAndroid.show(response.data.message, ToastAndroid.LONG)
                    callback()

                }
            }
        ).catch((e) => {
            console.log('fav error', e)
            // dispatch({ type: "loadActivityIndicator" });
        })
    } catch (error) {
        console.log('error is ', error)
        //   dispatch({ type: "loadActivityIndicator" });
    }
}

const onclickFilterApi = (dispatch) => async (data, callback = () => { }) => {
    try {
        axios({
            method: 'post',
            url: HOME_URL,
            data
        }).then(
            function (res) {
                // console.log('=====filter', res.data.data.properties)
                if (res.data.status == true) {
                    callback()
                    dispatch({ type: "updateHOMEDATA", payload: { homeData: res.data.data.properties.data } })
                    // setHomeData(res.data.data.properties.data)

                }
            }
        ).catch((e) => {
            console.log('error is', e);

        })
    } catch (error) {
        console.log('eee', error);
    }
}

const searchHomeApi = (dispatch) => async (data) => {
    try {
        axios({
            method: 'post',
            url: HOME_URL,
            data
        }).then(
            function (res) {
                // console.log('=====res,', res.data.data.properties.data)
                dispatch({ type: "updateHOMEDATA", payload: { homeData: res.data.data.properties.data } })
            }
        ).catch((e) => {
            console.log('error is ', e);
        })
    } catch (error) {
        console.log('eee', error);
    }
}
const homeApi = (dispatch) => async (data, headers,
    callback = () => { }
) => {
    dispatch({ type: "loadActivityIndicator" })
    try {

        axios({
            method: 'post',
            url: HOME_URL,
            data,
            headers

        })
            .then(
                async function (response) {
                    console.log("API Response Homes -----", response.data.data.properties.data);
                    if (response.data.data.category.current_page && response.data.data.properties.current_page == 1) {
                        dispatch({ type: "updateHomeData", payload: { data: response.data.data.category.data } })
                        dispatch({ type: "updateHOMEDATA", payload: { homeData: response.data.data.properties.data } })
                        callback()
                        dispatch({ type: "loadActivityIndicator" })
                    }

                    else {
                        dispatch({ type: "loadActivityIndicator" })
                        // setLoading(false)
                    }
                }
            )


    } catch (error) {
        dispatch({ type: "loadActivityIndicator" })
        // setLoading(false)
        console.log('eeee', error)
    }
}
const homeOnEndReachedApi = (dispatch) => async (data, headers, callback = () => { }) => {
    try {
        dispatch({ type: "loadActivityIndicator" })
        axios({
            method: 'post',
            url: HOME_URL,
            data,
            headers

        })
            .then(
                async function (response) {
                    //   console.log("API Response Home -----", response.data);
                    if (response.data.data.properties.current_page == 2) {
                        dispatch({ type: "updateHomeData", payload: [...data, response.data.data.category.data] })
                        // homeData.push(response.data.data.properties.data)
                        // dispatch({type:"updateHomeProperty",payload : {homeProperty: response.data.data.properties.data}})
                        callback()
                        // console.log('ddddd',propertyID)
                    }

                    else {
                        alert(response.data.message)
                        // setIsLoading(false)
                    }
                }
            )
            .catch((error) =>
                console.log('something went wrong ', error)
            );
        dispatch({ type: "loadActivityIndicator" })
        // setIsLoading(false)
    } catch (error) {
        dispatch({ type: "loadActivityIndicator" })
        console.log('eeee', error)
    }
}

const homeDetailsApi = (dispatch) => async (data, callback = () => { }) => {
    try {
        dispatch({ type: "loadActivityIndicator" });

        axios({
            method: 'post',
            url: Home_Details,
            data,


        }).then(
            function (response) {
                if (response.data.status == true) {
                    // console.log('Home Detail api', response.data.get_amenities)
                    dispatch({ type: 'updateHomeDetails', payload: { details: response.data } })
                    dispatch({ type: 'updateHomeDetailsData', payload: { homeDetailsData: response.data.get_amenities } })
                    dispatch({ type: 'updateHomeDetailsExtraServices', payload: { homeDetailsExtraServices: response.data.get_extra_service } })
                    callback()
                } else {
                }
            }
        ).catch((e) => {
            console.log('something went wrong', e)
            dispatch({ type: "loadActivityIndicator" });

        })
    } catch (error) {
        dispatch({ type: "loadActivityIndicator" });

        console.log('error', error);
    }
}

const getToFavourite = (dispatch) => async (data, headers, callback = () => { }) => {
    try {
        dispatch({ type: "loadActivityIndicator" });
        axios({
            method: 'get',
            url: my_favorites,
            data,
            headers
        }).then(
            function (response) {
                // console.log('myFavourite api', response.data.data)
                if (response.data.status === true) {
                    dispatch({ type: 'updateFavourite', payload: { homeDetails: response.data.data } })
                    callback()
                } else {
                    alert(response.data.message)
                    dispatch({ type: "loadActivityIndicator" });
                }
            }
        ).catch((e) => {
            console.log('something went wrong favourite', e);
            dispatch({ type: "loadActivityIndicator" });
        })
    } catch (error) {
        console.log('eee', error);
        dispatch({ type: "loadActivityIndicator" });
    }
}

const getAllServicesApi = (dispatch) => async (data, callback = () => { }) => {
    try {
        axios({
            method: 'get',
            url: getAllServicesApiUse,
            data
        }).then(
            function (response) {
                // console.log('===service',response.data.data)
                if (response.data.status == true) {
                    dispatch({ type: "updateAllServices", payload: { services: response.data.data } })
                }
            }
        ).catch((e) => {
            console.log('eee', e);
        })
    } catch (error) {
        console.log("error is", error);
    }
}
const getAllCategoryApi = (dispatch) => async (data, callback = () => { }) => {
    try {
        axios({
            method: 'post',
            url: getAllCategory,
            data
        }).then(
            function (response) {
                // console.log('===reso',response.data.data)
                if (response.data.status == true) {
                    dispatch({ type: "updateAllCategory", payload: { category: response.data.data } })
                }
            }
        ).catch((e) => {
            console.log('eee', e);
        })
    } catch (error) {
        console.log("error is", error);
    }
}

export const { Provider, Context } = createDataContext(
    homeReducer,
    {

        addToFavourite,
        // homeApi,
        // onclickFilterApi,
        // searchHomeApi,
        homeDetailsApi,
        // getToFavourite,
        getAllCategoryApi,
        getAllServicesApi,
        // homeOnEndReachedApi

    },
    { USER_ID: null, errorMessage: "", activityIndicator: false, data: [], homeData: [], details: [], homeDetailsData: [], category: [], services: [], homeDetailsExtraServices: [], homeProperty: [] }
)