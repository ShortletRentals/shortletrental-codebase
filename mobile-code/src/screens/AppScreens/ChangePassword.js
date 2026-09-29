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
import { useContext } from "react";
import { Context as AuthContext } from "../../context/AuthContext";
import { Toast } from "react-native-toast-message/lib/src/Toast";
import { useFocusEffect, useNavigation } from "@react-navigation/native";
import { Change_Password } from "../../network/Webconstant";
import { ActivityIndicator } from "react-native";


const ChangePassword = (props) => {

    const [mobileNumber, setMobileNumber] = useState('');
    const [confirmPassword, setConirmPassword] = useState('');
    const [password, setPassword] = useState('')
    const [token, setToken] = useState('')
    const [isSelect, setIsSelect] = useState(true)
    const [select, setSelect] = useState(true)
    const [newSelect, setNewSelect] = useState(true)
    const navigation = useNavigation()
    const [loader, setLoader] = useState(false)

    // const goBack = () => {
    //     props.navigation.navigate('Account')

    //   }
    //   useEffect(() => {
    //     BackHandler.addEventListener('hardwareBackPress', goBack)
    //     return () => { BackHandler.removeEventListener('hardwareBackPress', goBack) }
    //     // return task.remove()
    //   }, [])

    const handler = () => {
        props.navigation.navigate('Account')
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
        }, [props]))
    const validationData = () => {
        if (mobileNumber == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter old password'
            })

            // alert('please enter old password')
        }
        else if (password.trim() == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter new password'
            })

            // alert('please enter new password')
        }else if(mobileNumber == password){
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'Old password and New password both are not same.'
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
            } else if (confirmPassword.trim() == '') {
                Toast.show({
                    type: 'error',
                    text1: 'Shortlet',
                    text2: 'please enter confirm password '
                })

                // alert('please enter confirm password ')
            }
            else if (confirmPassword !== password) {
                Toast.show({
                    type: 'error',
                    text1: 'Shortlet',
                    text2: 'password not match'
                })

                // alert('password not match')
            } else {
                changePasswordApi()
            }
        }

        // else if (confirmPassword.trim() == '') {
        //     Toast.show({
        //         type: 'error',
        //         text1: 'please enter confirm password '
        //     })

        //     // alert('please enter confirm password ')
        // }

    }

    // const { changePasswordApi, state: { Token_ID } } = useContext(AuthContext)

    const changePasswordApi = async () => {
        setLoader(true)
        const Localtoken = await AsyncStorage.getItem('token_id');
        try {
            axios({
                method: 'post',
                url: Change_Password,
                data: {
                    old_password: mobileNumber,
                    new_password: password
                },
                headers: { "content-type": "application/json", "Authorization": `Bearer ${Localtoken}` }
            })
                .then(function (response) {
                    if (response.data.status === true) {
                        Toast.show({
                            type: 'success',
                            text1: 'Shortlet',
                            text2: response.data.message,
                        });
                        setLoader(false)
                        props.navigation.goBack()
                        setMobileNumber('')
                        setConirmPassword('')
                        setPassword('')
                    } else {
                        Toast.show({
                            type: 'error',
                            text1: 'Shortlet',
                            text2: response.data.message,
                        });
                        setLoader(false)
                    }
                })
        } catch (e) {
            console.log('errr', e);
            setLoader(false)
        }
    }

    return (
        <View style={commonStyles.container}>

            <Header props={props} Heading={'Change Password'} onPress={() => props.navigation.navigate('Account')} />
            <ScrollView contentContainerStyle={{ flexGrow: 1, backgroundColor: '#fff' }}>
                {/* <Text>{JSON.stringify(token)}</Text> */}
                <View style={{ marginTop: 20, flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', height: 50, backgroundColor: color.appTextBackgoundColor, marginHorizontal: 20, borderRadius: 10 }}>
                    <TextInput
                        placeholder={'Old Password'}
                        value={mobileNumber}
                        onChangeText={(e) => setMobileNumber(e)}
                        secureTextEntry={select}
                        placeholderTextColor='#000'
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
                        }}
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
                        placeholder={'New Password'}
                        placeholderTextColor='#000'
                        value={password}
                        onChangeText={(e) => setPassword(e)}
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
                        }}
                    // secureTextEntry={isSelect}
                    />
                    <TouchableOpacity onPress={() => setIsSelect(!isSelect)}>
                        <Image source={isSelect ? Images.hiddenIcon : Images.eyeIcon} style={{ height: 20, width: 20, marginRight: 20 }} />
                    </TouchableOpacity>
                </View>
                {/* <TextView
                    placeholder={'New Password'}
                    value={password}
                    onChangeText={(e) => setPassword(e)}
                /> */}
                <FlashMessage position={'center'} />
                <View style={{ marginTop: 20, flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', height: 50, backgroundColor: color.appTextBackgoundColor, marginHorizontal: 20, borderRadius: 10 }}>
                    <TextInput
                        placeholder={'Confirm Password'}
                        placeholderTextColor='#000'
                        value={confirmPassword}
                        onChangeText={(e) => setConirmPassword(e)}
                        secureTextEntry={newSelect}
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
                        }}
                    // secureTextEntry={isSelect}
                    />
                    <TouchableOpacity onPress={() => setNewSelect(!newSelect)}>
                        <Image source={newSelect ? Images.hiddenIcon : Images.eyeIcon} style={{ height: 20, width: 20, marginRight: 20 }} />
                    </TouchableOpacity>
                </View>
                {/* <TextView
                    placeholder={'Confirm Password'}
                    value={confirmPassword}
                    onChangeText={(e) => setConirmPassword(e)}
                /> */}
                <FlashMessage position={'center'} />
                <TouchableOpacity activeOpacity={0.5} onPress={() => validationData()} style={{
                    marginTop: 20,
                    backgroundColor: color.appOrangeColor,
                    // width: '90%',
                    padding: 10,
                    borderRadius: 10,
                    flexDirection: 'row', alignItems: 'center',
                    // marginLeft: 15,
                    height: 50,
                    marginHorizontal: 20
                }}>
                    <View style={{ flex: 0.6, flexDirection: 'row' }}>
                        <View style={{
                            // position:'absolute'
                            flex: 1,
                        }}>
                            <Image source={Images.whiteDot} style={{
                                paddingLeft: 70,
                                width: 25, height: 25, resizeMode: 'contain',
                            }} />
                        </View>
                        <Text style={{ textAlign: "center", fontSize: 14, color: color.appWhiteColor, alignSelf: 'center' }}>Change</Text>
                    </View>
                    <View style={{ marginLeft: 0, flex: 0.5 }}>
                        {
                            loader ? <ActivityIndicator size={'small'} color='#fff' /> : null
                        }
                    </View>
                </TouchableOpacity>


            </ScrollView>
        </View>


    )
}

export default ChangePassword;