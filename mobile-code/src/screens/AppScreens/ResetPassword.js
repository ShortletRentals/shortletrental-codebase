import React, { useState, useEffect } from "react";
import { View, Image, Text, ImageBackground, TouchableOpacity, _Text, ScrollView, TextInput, BackHandler } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import TextView from "../../components/TextView";
import axios from "axios";
import FlashMessage, { showMessage } from "react-native-flash-message";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { Reset_Password } from "../../network/Webconstant";
import { useFocusEffect, useRoute } from "@react-navigation/native";
import { Toast } from "react-native-toast-message/lib/src/Toast";
const ResetPassword = (props) => {
    const route = useRoute()
    let otps = props?.route?.params?.otp
    console.log('====otp reset screen', props.route.params.country);
    let isMobile = route?.params?.mobile
    // console.log('===', isMobile);
    const [mobileNumber, setMobileNumber] = useState('');
    const [confirmPassword, setConirmPassword] = useState('');
    const [password, setPassword] = useState('')
    // const [otp ,setOtp] = useState('')
    const [token, setToken] = useState('')
    const [isSelect, setIsSelect] = useState(true)
    const [select, setSelect] = useState(true)
    // setOtp(otps)

    const getData = async () => {
        let mobile = await AsyncStorage.getItem('mobile')
        // let Password = await AsyncStorage.getItem('password')
        setMobileNumber(mobile)
        // setPassword(Password)
        // console.log('mopa',mobile , pass)
    }

    // console.log('ottttttt',otps)

    useEffect(() => {
        getData()
    }, [])
    const handler = () => {
        props.navigation.goBack()
    }
    useFocusEffect(
        React.useCallback(() => {
            const backAction = () => {
                handler()
                return true;
            };

            const backHandler = BackHandler.addEventListener(
                'hardwareBackPress',
                backAction,
            );

            return () => backHandler.remove();
        }, [props])
    )
    const changePasswordHandler = () => {
        if (password == '') {
            Toast.show({
                type: 'error',
                text1: "Shortlet",
                text2: 'Please enter new password',
            })
        }
        else if (password !== '') {
            var regularExpression = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,15})/

            if (regularExpression.test(password) === false) {
                Toast.show({
                    type: 'error',
                    text1: 'Your password must contain at least (1) lowercase, (1)',
                    text2: ' uppercase letter, (1) special character and minimum 8 characters.'
                })
                // alert(' ')
            }
            else if (confirmPassword == '') {
                Toast.show({
                    type: 'error',
                    text1: "Shortlet",
                    text2: 'Please enter confirm password',


                })
            }
            else if (password !== confirmPassword) {
                Toast.show({
                    type: 'error',
                    text1: "Shortlet",
                    text2: 'Please enter correct password',



                })
            } else {
                onSubmitOtpFormHandler()
            }
        }
    }
    const onSubmitOtpFormHandler = () => {
        console.log("handle signup called");

        axios({
            method: "post",
            url: Reset_Password,
            data: {
                country_code: props.route.params.country,
                mobile: isMobile,
                otp: otps,
                password: password,
            },
            // headers: { "content-type": "application/x-www-form-urlencoded" }
        })
            .then(
                async function (response) {
                    // console.log("API Response -----", response);
                    if (response.data.status == true) {
                        props.navigation.navigate('Logins')
                        let data = {
                            mobileNumber:isMobile,
                            conformPassword:password,
                          };
                        await AsyncStorage.setItem('userData_Local',JSON.stringify(data))
                        Toast.show({
                            type: 'success',
                            text1: "Shortlet",
                            text2: response.data.message,
                        })
                    } else {
                        Toast.show({
                            type: 'error',
                            text1: "Shortlet",
                            text2: response.data.message,
                        })
                    }


                }
            )
            .catch((error) => console.log(error));
    }
    return (
        <View style={commonStyles.container}>

            <Header props={props} Heading={'Change Password'} onPress={() => props.navigation.goBack()} />
            <ScrollView contentContainerStyle={{ flexGrow: 1, marginTop: 20 }}>
                <View style={{ marginTop: 0, flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', height: 50, backgroundColor: color.appTextBackgoundColor, marginHorizontal: 20, borderRadius: 10 }}>
                    <TextInput
                        placeholder={'New Password'}
                        value={password}
                        onChangeText={(e) => setPassword(e)}
                        secureTextEntry={select}
                        style={{
                            // paddingLeft: 15,
                            height: 50,
                            width: '80%',
                            margin: 10,
                            backgroundColor: color.appTextBackgoundColor,
                            borderRadius: 10,
                            // marginLeft: 20,
                            // marginRight: 20,
                            fontSize: 15, color: '#000'
                        }}
                        placeholderTextColor={color.appTextColor}
                    // secureTextEntry={isSelect}
                    />
                    <TouchableOpacity onPress={() => setSelect(!select)}>
                        <Image source={select ? Images.hiddenIcon : Images.eyeIcon} style={{ height: 20, width: 20, marginRight: 20 }} />
                    </TouchableOpacity>
                </View>
                {/* <TextView
                    placeholder={'Old Password'}
                    value={mobileNumber}
                    onChangeText={(e) => setMobileNumber(e)}
                /> */}
                <View style={{ marginTop: 20, flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', height: 50, backgroundColor: color.appTextBackgoundColor, marginHorizontal: 20, borderRadius: 10 }}>
                    <TextInput
                        placeholder={'Confirm Password'}
                        value={confirmPassword}
                        onChangeText={(e) => setConirmPassword(e)}
                        secureTextEntry={isSelect}
                        style={{
                            // paddingLeft: 15,
                            height: 50,
                            width: '80%',
                            margin: 10,
                            backgroundColor: color.appTextBackgoundColor,
                            borderRadius: 10,
                            // marginLeft: 20,
                            // marginRight: 20,
                            fontSize: 15,
                            color: '#000'
                        }} placeholderTextColor={color.appTextColor}
                    // secureTextEntry={isSelect}
                    />
                    <TouchableOpacity onPress={() => setIsSelect(!isSelect)}>
                        <Image source={isSelect ? Images.hiddenIcon : Images.eyeIcon} style={{ height: 20, width: 20, marginRight: 20 }} />
                    </TouchableOpacity>
                </View>
                <TouchableOpacity onPress={changePasswordHandler}
                    style={{
                        marginTop: 20,
                        backgroundColor: color.appOrangeColor,
                        width: '90%',
                        padding: 10,
                        borderRadius: 10,
                        justifyContent: 'center',
                        marginHorizontal: 20,
                        height: 50
                    }}>
                    <View style={{
                        position: 'absolute',
                        flex: 1,
                    }}>
                        <Image source={Images.whiteDot} style={{
                            paddingLeft: 70,
                            width: 25, height: 25, resizeMode: 'contain',
                        }} />
                    </View>
                    <Text style={{ textAlign: "center", fontSize: 14, color: color.appWhiteColor }}>Change</Text>
                </TouchableOpacity>


            </ScrollView>
        </View>


    )
}

export default ResetPassword;