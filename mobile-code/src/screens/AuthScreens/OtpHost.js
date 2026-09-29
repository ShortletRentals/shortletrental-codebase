import React, { useState, useEffect, useCallback } from "react";
import { View, Image, Text, ImageBackground, StyleSheet, TextInput, TouchableOpacity, _Text, ScrollView, ActivityIndicator, NativeSyntheticEvent, Alert } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import axios from "axios";
import {
    CodeField,
    Cursor,
    useBlurOnFulfill,
    useClearByFocusCell,
} from 'react-native-confirmation-code-field';
import FlashMessage, { showMessage } from "react-native-flash-message";
import { Send_Otp, Verify_Otp, becomeahost_signUp } from "../../network/Webconstant";
import { useContext } from "react";
import { Context as AuthContext } from "../../context/AuthContext";
import AsyncStorage from "@react-native-async-storage/async-storage";
// import OtpAutoFillViewManager from "react-native-otp-auto-fill";
import { Toast } from "react-native-toast-message/lib/src/Toast";
import { useRoute } from "@react-navigation/native";

const OtpHost = ({ navigation }) => {
    const route = useRoute()
    const [otpWarn, setOtpWarn] = useState('')
    const [type, setType] = useState(route.params.type)

    const [value, setValue] = useState('');
    const ref = useBlurOnFulfill({ value, cellCount: CELL_COUNT });
    const [props, getCellOnLayoutHandler] = useClearByFocusCell({
        value,
        setValue,
    });
    const [timer, setTimer] = useState(59);
    const timeOutCallback = useCallback(() => setTimer(currTimer => currTimer - 1), []);
    const [showLoader, setShowLoader] = useState(false)



    useEffect(() => {
        // getUserID()
        timer > 0 && setTimeout(timeOutCallback, 1000);
    }, [timer, timeOutCallback]);


    // const resetTimer = function () {
    //     if (!timer) {
    //         setTimer(60);
    //     }
    // };
    const resetTimer = function () {
        if (timer > 0) {

        } else {
            setTimer(60);
        }
    };
console.log('-------------------timer',timer)
    // const getUserID = async () => {
    //     let userID = await AsyncStorage.getItem('USER')
    //     console.log('========userID', userID);
    // }
    const CELL_COUNT = 4;
    const { verifyOtpHandler, resendOtpHandler, state: { Mobile, Password, country_code, Otp } } = useContext(AuthContext)
    const Mobiles = route?.params?.mobiles
    // console.log('======ffff', route?.params?.MOBILE)
    // console.log("======>>>><<<<<", route?.params?.Newdata);

    // const handleComplete = ({
    //     nativeEvent: { code },
    // }: NativeSyntheticEvent<{ code: string }>) => {
    //     Alert.alert('OTP Code Received!', code)
    // };

    // // This is only needed once to get the Android Signature key for SMS body
    // const handleOnAndroidSignature = ({
    //     nativeEvent: { code },
    // }: NativeSyntheticEvent<{ code: string }>) => {
    //     console.log('Android Signature Key for SMS body:', code);
    // };

    const onSubmitOtpFormHandler = () => {
        // console.log("handle verify called");
        console.log(value)
        setShowLoader(true)
        try {
            axios({
                method: 'post',
                url: Verify_Otp,
                data: {
                    country_code: route?.params?.Newdata?.country_code,
                    mobile: route?.params?.Newdata?.mobile,
                    otp: value,
                    // email: route?.params?.Newdata?.email,
                }
            })
                .then(function (response) {

                    if (response.data.status == true) {
                        becomeAHostSignin();
                        
                        setShowLoader(false)
                        // navigation.navigate('AddPropertyguest', { type: 1, });
                        // if (route?.params?.from === 'SignUp') {
                        //     setShowLoader(false)
                        //     navigation.navigate('Logins')
                        // } else {
                        //     setShowLoader(false)
                        //     navigation.navigate('ResetPassword', { otp: value, mobile: Mobiles })
                        // }
                    } else {
                        // navigation.navigate('AddPropertyguest', { type: 1, id: userId });
                        Toast.show({
                            type: 'error',
                            text1: 'Shortlet',
                            text2: response.data.message,
                        });
                        setShowLoader(false)
                    }
                })

        } catch (error) {
            console.log('error is', error);
            setShowLoader(false)
        }



    }

    console.log('==========gender', route.params.Newdata,route.params.EMAIL);
    const becomeAHostSignin = () => {
        let data = route?.params?.Newdata
        try {
            axios({
                method: 'post',
                url: becomeahost_signUp ,// 'https://zea-virtual-events.com/shortletrental/api/auth/becomeahost_signUp',
                data,
                // headers: { "content-type": "application/x-www-form-urlencoded" }
            })
                .then(async function (response) {
                    console.log("API Response Login ---========--", response.data);

                    if (response.data.status == true) {
                        console.log('called true', response.data.data.id);
                        // userId = response.data.data.id;
                        AsyncStorage.setItem('USER', JSON.stringify(response.data.data.id))
                        let get = await AsyncStorage.getItem('USER')
                        console.log('user id is ', get);

                        Toast.show({
                            type: 'success',
                            text1: 'Shortlet',
                            text2: response.data.message,
                        });
                        Toast.show({
                            type: 'success',
                            text1: 'Shortlet',
                            text2: "Otp Verify Successfully",
                        });
                        navigation.navigate('AddPropertyguest', { type: 1, });
                    } else {
                        console.log('called false');
                        Toast.show({
                            type: 'error',
                            text1: 'Shortlet',
                            text2: response.data.message,
                        });
                    }
                })
                .catch(error => console.log(error));
        } catch (error) {
            console.log(error);
        }
    };

    const onResendOtpHandler = () => {
        // console.log("handle resend otp called");
        let data = {
            type: 'resend',
            country_code,
            mobile: route?.params?.MOBILE,
            full_name:route?.params?.Newdata?.fullname,
            email:route?.params?.Newdata?.email

        }
        resendOtpHandler(data, () => {
            setValue("")
            resetTimer()
            // navigation.navigate('Otp')
        })


    }
    return (
        <View style={commonStyles.container}>
            <Header Heading={'OTP'} onPress={() => navigation.navigate('BecomeaHost')} />
            <FlashMessage position={'top'} />
            <ScrollView>
                <Image source={Images.mobileIcon} style={{
                    width: width / 2, height: height / 3.8, resizeMode: 'stretch',
                    alignSelf: "center", marginTop: 10,
                }} />
                <Text style={{ fontSize: 24, fontWeight: '600',alignSelf:'center', marginTop: 20, marginHorizontal: 20, color: color.primaryColorBlack }}>OTP Verification</Text>
                <View style={{ flexDirection: 'row', marginTop: 5,alignSelf:'center' }}>
                    <Text style={{ paddingLeft: 20,color:'#000' }}>Enter The OTP sent to  </Text>
                    <Text style={{ fontWeight: 'bold', fontSize: 15, color: color.primaryColorBlack ,width:width/2}}>
                        {/* +{route?.params?.Newdata?.country_code} {route?.params?.MOBILE} */}
                        {route.params.EMAIL}
                        </Text>
                </View>

                <View style={{ flexDirection: 'row', marginTop: 12, alignSelf: 'center' }}>
                    <CodeField
                        ref={ref}
                        {...props}
                        // Use `caretHidden={false}` when users can't paste a text value, because context menu doesn't appear
                        value={value}
                        onChangeText={setValue}
                        cellCount={CELL_COUNT}
                        rootStyle={styles.codeFieldRoot}
                        keyboardType="number-pad"
                        textContentType="oneTimeCode"

                        renderCell={({ index, symbol, isFocused }) => (
                            <Text
                                key={index}
                                style={[styles.cell, isFocused && styles.focusCell]}
                                onLayout={getCellOnLayoutHandler(index)}>
                                {symbol || (isFocused ? <Cursor /> : null)}
                            </Text>
                        )}

                    />
                    <FlashMessage position={'center'} />

                </View>
                <Text style={{ color: 'red', marginHorizontal: 20 }}>{otpWarn}</Text>
                {/* <OtpAutoFillViewManager
                    onComplete={handleComplete}
                    onAndroidSignature={handleOnAndroidSignature}
                    // style={styles.box}
                    length={4} // Define the length of OTP code. This is a must.

                /> */}
                {
                    showLoader ? <View style={{ top: 40, right: -95, zIndex: 1000 }}><ActivityIndicator size={'small'} color='#fff' /></View> : null
                }
                {/* {type == 1 ?
                    <TouchableOpacity
                        activeOpacity={0.5}
                        onPress={() => [navigation.navigate('AddPropertyguest', { id: route.params.id })]}
                        // onPress={()=>props.navigation.navigate('BottomTab')} 
                        style={{
                            marginTop: 5,
                            backgroundColor: color.appBlueColor,
                            // width: width - 30,
                            padding: 10,
                            borderRadius: 10,
                            justifyContent: 'center',
                            // marginLeft: 15,
                            height: 50,
                            width: '90%',
                            marginHorizontal: 20
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

                        <Text style={{ textAlign: "center", fontSize: 16, color: color.appWhiteColor }}>Verify and Proceed</Text>

                    </TouchableOpacity> : */}
                <TouchableOpacity
                    activeOpacity={0.5}
                    onPress={onSubmitOtpFormHandler}
                    // onPress={()=>props.navigation.navigate('BottomTab')} 
                    style={{
                        marginTop: 5,
                        backgroundColor: color.appBlueColor,
                        // width: width - 30,
                        padding: 10,
                        borderRadius: 10,
                        justifyContent: 'center',
                        // marginLeft: 15,
                        height: 50,
                        width: '90%',
                        marginHorizontal: 20
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

                    <Text style={{ textAlign: "center", fontSize: 16, color: color.appWhiteColor }}>Verify and Proceed</Text>

                </TouchableOpacity>

                {/* } */}

                <Text style={{ alignSelf: "center", marginTop: 24,color:'#000' }}>  00:{timer?.toString()?.length < 2 ? `0${timer}` : timer}</Text>
                <View style={{ flexDirection: 'row', alignSelf: 'center', marginTop: 15 }}>
                    <Text style={{ fontSize: 16, marginHorizontal: 3,color:'#000' }}>Don't Recieve the OTP ?</Text>
                    {
                        timer !== 0 ?
                            <View>
                                <Text style={{ color: color.appOrangeColor, fontSize: 16 }}>Resend OTP</Text>
                            </View> :

                            <TouchableOpacity onPress={() => onResendOtpHandler()}>
                                <Text style={{ color: color.appOrangeColor, fontSize: 16 }}>Resend OTP</Text>
                            </TouchableOpacity>}
                </View>
            </ScrollView>
        </View>
    )
}
// const TextStyle = StyleSheet.create({
//     input: {
//         height: 50,
//         width:60,
//         margin: 8,
//         backgroundColor:color.appTextBackgoundColor,
//         borderRadius:4,
//         padding: 15,
//         textAlign:'center',
//       },

// })
const styles = StyleSheet.create({
    root: { flex: 1, padding: 20 },
    title: { textAlign: 'center', fontSize: 20 },
    codeFieldRoot: { marginTop: 20 },
    cell: {
        width: 72,
        height: 50,
        lineHeight: 38,
        padding: 6,
        fontSize: 24,
        borderWidth: 2,
        borderColor: '#00000030',
        textAlign: 'center',
        margin: 8,
        borderRadius: 4,
        backgroundColor: '#F7F6FC',color:'#000'

    },
    focusCell: {
        borderColor: '#000',
    },
});
export default OtpHost;