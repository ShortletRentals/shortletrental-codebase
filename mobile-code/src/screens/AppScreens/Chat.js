import React, { useCallback, useEffect, useState } from "react";
import { View, Image, Text, ImageBackground, StyleSheet, TextInput, TouchableOpacity, _Text, ScrollView, InteractionManager, Alert } from "react-native";
import { color, height, width } from "../../styles/colors";
import { commonStyles } from "./../../styles/style";
import Header from "../../components/Header";
import { useFocusEffect, useNavigation, useRoute } from "@react-navigation/native";
import axios from "axios";
import { ChatApi, MAIN_URL } from "../../network/Webconstant";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { Toast } from "react-native-toast-message/lib/src/Toast";
import moment from "moment";
import { SliderShimmerNotification } from "../../components/Skeleton";
import { BackHandler } from "react-native";
import Images from "../../styles/Images";

let USER = ''
const Chat = (props) => {

    const [chatData, setChatData] = useState([])
    const [loader, setLoader] = useState(false)


    const handler = () => {
        props?.route?.params?.fromDetail == 'fromDetail' ?
            props.navigation.navigate('HomeDetails', { propertyid: props?.route?.params?.propertyid }) :

            // props?.route?.params?.fromHome == "HomeScreen" ? props.navigation.navigate('Home'):
            //      props?.navigation?.navigate('Notification')
            props.route.params.notificationChat == 'Notify' ? props.navigation.navigate('Notification') : props.route.params.notificationChat == undefined ? props.navigation.navigate('Home') : ''
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
    useEffect(() => {
        const userList = async () => {
            let user_id
             = await AsyncStorage.getItem('USER_ID')
             USER = user_id
             console.log(USER)
            const Localtoken = await AsyncStorage.getItem('token_id');
            if (Localtoken == null) {
                Alert.alert('Hold on!', 'Please login first?', [
                    {
                        text: 'Cancel',
                        onPress: () => null,
                        style: 'cancel',
                    },
                    { text: 'Login', onPress: () => props?.navigation?.navigate('Logins') },
                ]);
            } else {
                getChatApi(Localtoken)
            }
        }
        userList()

    }, [props,USER])

 

    const getChatApi = async (Localtoken) => {
        setLoader(true)
        try {
            await axios({
                method: 'get',
                url: ChatApi,
                headers: {
                    "Accept": "application/json",
                    "Authorization": `Bearer ${Localtoken}`
                },
            }).then(
                function (res) {
                    console.log('chat api is', res.data.data);
                    if (res.data.success == true) {
                       
                        setChatData(res.data.data)
                        setLoader(false)
                    } else {
                        // Toast.show({
                        //     type: 'error',
                        //     text1: 'Shortlet',
                        //     text2: res.data.message
                        // })
                        let arr = []
                        arr.push({
                            id: 225,
                            sender_detail: {
                                name: "Shortlet Rental Support",
                                image: "",
                            },
                            booking_id: "",
                            sender_id: USER,
                            receiver_id: "1",
                            last_message: {
                                created_at: new Date().getTime(),
                                message: "hello"
                            }
                        })
                        setChatData(arr)
                        setLoader(false)
                    }
                }
            )
        } catch (error) {
            console.log('error chat', error);
            setLoader(false)
        }
    }
    // console.log('--------------------notifiy', props.route.params.notificationChat);
    return (
        <View style={[commonStyles.container, { backgroundColor: color.appTextBackgoundColor, }]}>

            <Header props={props} Heading={'Chat'} onPress={() =>
                // props?.navigation?.navigate('Home')
                props?.route?.params?.fromDetail == 'fromDetail' ?
                    props.navigation.navigate('HomeDetails', { propertyid: props?.route?.params?.propertyid }) :

                    // props?.route?.params?.fromHome == "HomeScreen" ? props.navigation.navigate('Home'):
                    //      props?.navigation?.navigate('Notification')
                    props.route.params.notificationChat == 'Notify' ? props.navigation.navigate('Notification') : props.route.params.notificationChat == undefined ? props.navigation.navigate('Home') : ''

            } />
            <View style={{ marginTop: 0, marginBottom: 20 }}>
                {
                    loader ? <SliderShimmerNotification /> :
                        <>
                            <ScrollView style={{ marginTop: 0 }}>
                                <View style={{ marginBottom: 10 }} />
                                <View style={{ marginBottom: 150 }}>
                                    {
                                        chatData.length == 0 ?
                                            <View style={{
                                                justifyContent: 'center', alignItems: 'center', marginTop: 150
                                            }}>
                                                <Image source={Images.No_Data} style={{ height: 200, width: 200 }} />
                                                <Text style={{ alignSelf: 'center', color: '#000', fontSize: 20, justifyContent: 'center', }}>No Data Found</Text></View> :

                                            chatData?.map((item, index) => {
                                                // console.log('item', item.last_message.updated_at)

                                                return (
                                                    <TouchableOpacity key={index} onPress={() => props.navigation.navigate('ChatMessage', { chatID: item?.id, chatName: item?.sender_detail?.name, chatImage: item?.sender_detail?.image, Book_ID: item?.booking_id, sender_id: item.sender_id, receiver_id: item.receiver_id, notificationChat: props.route.params.notificationChat, sent_name: item?.last_message?.sent_by_detail?.name })} style={{ marginTop: 10, borderRadius: 10, marginHorizontal: 20, backgroundColor: color.appWhiteColor }} >
                                                        <View style={{ flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between', }}>
                                                            <View style={{ flexDirection: 'row', }}>
                                                                <Image source={{ uri: item?.sender_detail?.image == undefined ? `${MAIN_URL}/images/default_user.png` : item?.sender_detail?.image }} style={{ marginLeft: 10, height: 40, width: 40, marginTop: 10, marginBottom: 10, borderRadius: 20 }} />
                                                                <View style={{ alignSelf: 'flex-start' }}>
                                                                    <Text style={{ marginHorizontal: 7, marginTop: 6, fontSize: 14, color: '#000', fontWeight: 'bold' }}>{item?.sender_detail?.name == null ? 'Kamal' : item?.sender_detail?.name}</Text>
                                                                    <Text numberOfLines={1} style={{ marginHorizontal: 10, color: color.appTextColor, marginBottom: 6 }}>{item?.last_message?.message == null ? '0' : item?.last_message?.message}</Text>
                                                                </View>
                                                            </View>
                                                            <Text style={{ marginRight: 10, color: '#3C317D' }}>{moment(item?.last_message?.created_at).format('hh:mm a')}</Text>
                                                        </View>
                                                    </TouchableOpacity>
                                                )
                                            })}
                                </View>
                            </ScrollView>
                        </>
                }
            </View>
            {/* <View style={{flex:0.1, marginBottom: 300 }} /> */}
        </View>
    )
}
export default Chat;