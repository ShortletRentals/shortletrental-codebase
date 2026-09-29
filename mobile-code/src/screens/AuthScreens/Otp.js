import React, { useState, useEffect, useCallback } from "react";
import { View, Image, Text, ImageBackground, StyleSheet, TextInput, TouchableOpacity, _Text, ScrollView, ActivityIndicator, NativeSyntheticEvent, Alert, BackHandler, Platform } from "react-native";
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
import { Send_Otp, Sign_UP, Verify_Otp } from "../../network/Webconstant";
import { useContext } from "react";
import { Context as AuthContext } from "../../context/AuthContext";
import AsyncStorage from "@react-native-async-storage/async-storage";
// import OtpAutoFillViewManager from "react-native-otp-auto-fill";
import { Toast } from "react-native-toast-message/lib/src/Toast";
import { useFocusEffect, useRoute } from "@react-navigation/native";
import TimeCounter from "../../components/TimeCounter";
import messaging from '@react-native-firebase/messaging';


const Otp = ({ navigation }) => {
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
    const [token, setToken] = useState('');

    const [name, setName] = useState(route?.params?.NAME);
    const [mobileNumber, setMobileNumber] = useState(route?.params?.MOBILE);
    const [emailAddrss, setEmailAddrss] = useState(route?.params?.EMAIL);
    const [password, setPassword] = useState(route?.params?.PASSWORD);
    const [conformPassword, setConformPassword] = useState(route?.params?.CONFIRMPASSWORD);
    const [countryCode, setCountryCode] = useState(route?.params?.COUNTRYCODE);
    const [otp, setOtp] = useState(route?.params?.OTP);
    const Mobiles = route?.params?.mobiles
    const CountryForgot = route?.params?.countryForgot
    const ForgotOtp = route?.params?.FORGOTPASSWORD
    // const [loader,setLoader] = useState(false)

    console.log('======ffff', name,emailAddrss,mobileNumber,countryCode)

    useEffect(() => {
        
        checkToken()
      }, [props]);
    
      const checkToken = async () => {
        const fcmToken = await messaging().getToken();
        if (fcmToken) {
          console.log("---fcm-tokenn-------", fcmToken);
          setToken(fcmToken)
        }
      }
    useEffect(() => {
        timer > 0 && setTimeout(timeOutCallback, 1000);
    }, [timer, timeOutCallback]);

    const handler = () => {
        route.params.fromForgot == 'forgot' ? navigation.navigate('Logins') : navigation.navigate('SignUp')
    }
    useFocusEffect(
        React.useCallback(() => {
            const backAction = () => {
                handler()
                return true;
            };p;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;

            const backHandler = BackHandler.addEventListener(
                'hardwareBackPress',
                backAction,
            );

            return () => backHandler.remove();
        }, [props])
    )
    const resetTimer = function () {
        if (timer > 0) {

        } else {
            setTimer(60);
        }
    };
    const CELL_COUNT = 4;
    const { verifyOtpHandler, resendOtpHandler, state: { Mobile, Password, country_code, Otp } } = useContext(AuthContext)


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


    const handleSignup = async () => {
        // setLoader(true)
        try {
            await axios({
                method: 'post',
                url: Sign_UP,
                data: {
                    country_code: countryCode,
                    full_name: name,
                    email: emailAddrss,
                    mobile: mobileNumber,
                    password: password,
                    confirmpassword: conformPassword,
                    device_type: Platform.OS == "android" ? "Android" : "IOS",
                    device_token: token
                }
            })
                .then(async function (response) {
                    console.log("API---------------- Response SignUP  -----", response.data.token);
                    if (response.data.status === true) {
                        await AsyncStorage.setItem('token_id', response.data.token)
                        await AsyncStorage.setItem('USER_ID', JSON.stringify(response.data.data.id));
                        // setMoblie(response.data.data.mobile)
                        setOtp(response.data.data.otp)
                        navigation.reset({
                            index: 0,
                            routeNames: ['BottomTab'],
                            routes: [{ name: 'BottomTab' }]
                        })
                        Toast.show({
                            type: 'success',
                            text1: 'Shortlet',
                            text2: response.data.message,
                        });

                        // setLoader(false)
                        // props?.navigation?.navigate('Otp', { from: 'SignUp',MOBILE:mobileNumber });
                    }
                    else {
                        Toast.show({
                            type: 'error',
                            text1: 'Shortlet',
                            text2: response.data.message && response.data.message.email,
                        });
                        // setLoader(false)
                    }
                })

        } catch (error) {
            console.log('error verify', error);
        }


    };
    const onSubmitOtpFormHandler = () => {
        // console.log("handle verify called");
        setShowLoader(true)
        console.log('route', route.params.OTP);
        console.log('value length', value.length);
        if (value.length < 4) {
            Toast.show({
                type: 'success',
                text1: 'Shortlet',
                text2: "Please enter OTP first",
            });
            setShowLoader(false)
            return;
        }
        try {
            axios({
                method: 'post',
                url: Verify_Otp,
                data: {
                    country_code: countryCode || CountryForgot,
                    mobile: mobileNumber || Mobiles,
                    otp: value,
                    // route.params.OTP || ForgotOtp,
                    // otp: value || ForgotOtp,
                    password: Password
                }
            })
                .then(function (response) {
                    if (response.data.status == true) {
                        if (otp == otp || ForgotOtp) {
                            // alert('succcess')
                            setShowLoader(false)
                            Toast.show({
                                type: 'success',
                                text1: 'Shortlet',
                                text2: response.data.message,
                            });
                            if (route?.params?.from === 'SignUp') {
                                setShowLoader(false)
                                handleSignup()


                            } else {
                                setShowLoader(false)
                                navigation.navigate('ResetPassword', { otp: value, mobile: Mobiles ,country:countryCode || CountryForgot})
                                // navigation.navigate('ResetPassword', { otp: value, mobile: Mobiles })

                            }
                        } else {
                            Toast.show({
                                type: 'error',
                                text1: 'Shortlet',
                                text2: 'Otp not match',
                            });
                            setShowLoader(false)
                        }

                    } else {
                        Toast.show({
                            type: 'error',
                            text1: 'Shortlet',
                            text2: response.data.message,
                        });
                        setShowLoader(false)
                    }
                })
        } catch (error) {
            console.log('verify otp', error);
            setShowLoader(false)
        }




        // let data = {
        //     country_code,
        //     mobile: route?.params?.MOBILE || Mobiles,
        //     otp: value,
        //     password: Password
        // }
        // // console.log('=====data', data);
        // verifyOtpHandler(data, () => {
        //     // if(Otp == value){
        //     if (route?.params?.from === 'SignUp') {
        //         setShowLoader(false)
        //         navigation.navigate('Logins')
        //     } else {
        //         setShowLoader(false)
        //         navigation.navigate('ResetPassword', { otp: value, mobile: Mobiles })
        //     }

        // })

    }

    const onResendOtpHandler = () => {
        console.log("handle resend otp called");
        let data = {
            type: 'resend',
            country_code :countryCode || CountryForgot,
            mobile: route?.params?.fromForgot == 'forgot' ? route?.params?.mobiles : route?.params?.MOBILE,
            full_name: name,
            email: emailAddrss
        }
        resendOtpHandler(data, () => {
            setValue("")
            resetTimer()
            // navigation.navigate('Otp')
        })
    }
    return (
        <View style={commonStyles.container}>
            <Header Heading={'OTP'} onPress={() => route.params.fromForgot == 'forgot' ? navigation.navigate('Logins') : navigation.navigate('SignUp')} />
            <FlashMessage position={'top'} />
            <ScrollView>
                <Image source={Images.mobileIcon} style={{
                    width: width / 2, height: height / 3.8, resizeMode: 'stretch',
                    alignSelf: "center", marginTop: 10,
                }} />
                <Text style={{ fontSize: 24, fontWeight: '600', marginTop: 20, marginHorizontal: 20, color: color.primaryColorBlack }}>OTP Verification</Text>
                <View style={{ flexDirection: 'row', marginTop: 5 }}>
                    <Text style={{ paddingLeft: 20, color: '#000' }}>Enter The OTP sent to </Text>
                    <Text style={{ fontWeight: 'bold', fontSize: 15, color: color.primaryColorBlack,width:'50%' }}>
                        {/* {emailAddrss} */}
                        +{countryCode}{CountryForgot} {Mobiles}{mobileNumber}
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
                {type == 1 ?
                    <TouchableOpacity
                        activeOpacity={0.5}
                        onPress={() => [navigation.navigate('AddPropertyguest', { id: route.params.id })]}
                        // onPress={()=>props.navigation.navigate('BottomTab')} 
                        style={{
                            marginTop: 5,
                            backgroundColor: color.appOrangeColor,
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

                    </TouchableOpacity> :
                    <TouchableOpacity
                        activeOpacity={0.5}
                        onPress={onSubmitOtpFormHandler}
                        // onPress={()=>props.navigation.navigate('BottomTab')} 
                        style={{
                            marginTop: 5,
                            backgroundColor: color.appOrangeColor,
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

                }

                <Text style={{ alignSelf: "center", marginTop: 24, color: '#000' }}>  00:{timer?.toString()?.length < 2 ? `0${timer}` : timer}</Text>
                {/* <TimeCounter startCounting={true} resetOtpState={(e) => null} /> */}
                <View style={{ flexDirection: 'row', alignSelf: 'center', marginTop: 15 }}>
                    <Text style={{ fontSize: 16, marginHorizontal: 3, color: '#000' }}>Don't Recieve the OTP ?</Text>
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
        backgroundColor: '#F7F6FC', color: '#000'

    },
    focusCell: {
        borderColor: '#000',
    },
});
export default Otp;