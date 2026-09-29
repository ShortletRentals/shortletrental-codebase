import {
    AccessToken,
    GraphRequest,
    GraphRequestManager,
    LoginManager,
} from "react-native-fbsdk";
import React from "react";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { Platform } from 'react-native'
export const onFacebookLogin = () => new Promise((resolve, reject) => {
    // For facebook web login
    if (Platform.OS === "android") {
        LoginManager.setLoginBehavior("web_only")
    }
    LoginManager.logInWithPermissions([
        "public_profile",
        "email",
    ]).then(
        async function (result) {
            // console.log('result>>>>', result)
            if (result.isCancelled) {
                reject("Login cancelled");
            } else {
                const data = await AccessToken.getCurrentAccessToken();
                const fbAccessToken = data.accessToken;
                ////await AsyncStorage.setItem("facebookToken", fbAccessToken);
                //await AsyncStorage.setItem("facebookUserId", data.userID);
                const PROFILE_REQUEST_PARAMS = {
                    fields: {
                        string: 'id, email,picture.width(200).height(200),name,gender,location{location{city,state,region,country}},birthday,friends',
                    },
                };
                const profileRequest = new GraphRequest(
                    '/me',
                    { fbAccessToken, parameters: PROFILE_REQUEST_PARAMS },
                    (error, user) => {
                        if (error) {
                            reject("Something went wrong please try again")
                        } else {
                            // this.setState({userInfo: user});
                            resolve(user);
                        }
                    },
                );
                new GraphRequestManager().addRequest(profileRequest).start();

                // axios
                //     .get(
                //         "https://graph.facebook.com/v0.9/" +
                //             data.userID +
                //             "?fields=email,picture,name,gender,age_range,location{location{city,state,region,country}},friends&access_token=" +
                //             data.accessToken
                //     )
                //     .then(async function (response) {
                //       console.log('response>>>>>>>', response)
                //         // resolve(response.data);
                //         reject("sads")
                //     })
                //     .catch(function (error) {
                //         reject(error)
                //     });
            }
        },
        function (error) {
            reject("Login fail with error:asdasdasdas " + error);
        }
    );
});
