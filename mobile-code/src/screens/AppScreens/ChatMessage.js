import React, { useRef } from 'react'
import { Image, SafeAreaView, StyleSheet, Text, View, TouchableOpacity, TextInput, ScrollView, Pressable, FlatList, ActivityIndicator, InteractionManager, Button, Animated, Platform, StatusBar } from 'react-native'
import Header from '../../components/Header'
import Images from '../../styles/Images'
import { color } from '../../styles/colors'
import LinearGradient from 'react-native-linear-gradient'
import { useState } from 'react'
import { useFocusEffect, useIsFocused, useNavigation, useRoute } from '@react-navigation/native'
import { useEffect } from 'react'
import axios from 'axios';
import { Chat_Details } from '../../network/Webconstant'
import { useContext } from 'react'
import { Context as AuthContext } from '../../context/AuthContext'
import { Bubble, GiftedChat } from 'react-native-gifted-chat'
import { useCallback } from 'react'
import { useLayoutEffect } from 'react'

import { err } from 'react-native-svg/lib/typescript/xml'
import AsyncStorage from '@react-native-async-storage/async-storage'
import moment from 'moment'
import { Toast } from 'react-native-toast-message/lib/src/Toast'
import { createRef } from 'react'
import { KeyboardAvoidingView } from 'react-native'
import { width } from '../../styles/style'
import { BackHandler } from 'react-native'
import { socket } from '../../services/socket'
import { KeyboardAwareView } from 'react-native-keyboard-aware-view'
import { io } from 'socket.io-client'

let USER_ID = ''
let Chat_name = ''
const ChatMessage = (props) => {
    const [message, setMessage] = useState('')
    // const [messages, setMessages] = useState([
    //     {
    //         type: 'text',
    //         message: 'message',
    //         booking_id: ' route?.params?.Book_ID',
    //         sender_id: 'USER_ID',
    //         receiver_id: ' USER_ID == route?.params?.sender_id ? route?.params?.receiver_id : route?.params?.sender_id',
    //         chatId: 'route?.params?.chatID'
    //     }
    // ])
    const [messages, setMessages] = useState([])
    const [loader, setLoader] = useState(false)
    const [data, setData] = useState('')
    const route = useRoute()
    const [refreshing, setRefreshing] = useState(false);
    const list = useRef(null);
    // const { state: { Token_ID, USER_ID } } = useContext(AuthContext)
    let chatListRef = useRef(null)


    // socket.on('connect', () => {
    //     console.log(socket.connected); // true
    //     alert(socket.connected)
    //   });
    //   const socket = io.connect("https://emit.ae/",{
    //     reconnectionDelay: 1000,
    //     reconnection: true,
    //     reconnectionAttempts: Infinity,
    //     jsonp: false
    //   });
    //   alert(socket.connected)

    useEffect(() => {
       
        // console.log("",socket)
        let User = async () => {
            let USER = await AsyncStorage.getItem('USER_ID')
           
            USER_ID = USER
           
            
        }
        User()
       
        getChatDetailsApi()

    }, [props, USER_ID])

    useLayoutEffect(() => {
        // console.log("---------------",socket,'socket',props.route.params.MyBooking);
        let socketData = async () => {
            const sender_id = USER_ID
            const receiver_id = USER_ID == route?.params?.sender_id ? route?.params?.receiver_id : route?.params?.sender_id
            const roomID = sender_id.toString() + receiver_id.toString()
            console.log('roomid', roomID)
            socket.emit('join', roomID);

            socket.on('receivedMessage', function (msg) {
                // alert(msg.last_message.message)
                // console.log("receivedMessage",  msg.last_message.message)
                let msg1 = {
                    type: 'text',
                    message: msg.last_message.message,
                    booking_id: route?.params?.Book_ID,
                    sender_id: USER_ID,
                    sent_by: route?.params?.sender_id,
                    receiver_id: USER_ID == route?.params?.sender_id ? route?.params?.receiver_id : route?.params?.sender_id,
                    chatId: route?.params?.chatID
                }
                setMessages([msg1, ...messages])
            })

        }
        socketData()

    }, [messages])

    // console.log('-----------props',props.route.params.chatID,props.route.params.sender_id,props.route.params.receiver_id,props.route.params.Book_ID)

    const handler = () => {
        props.route.params.MyBooking == 'MyBooking' ? props.navigation.navigate('MyBooking') : props.navigation.navigate('Chat', { notificationChat: props?.route?.params?.notificationChat })
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




    const getChatDetailsApi = async (type) => {
        // console.log("ss")
        // setMessages([])
        setLoader(true)
        // if (type == "1") {

        // }
        const Localtoken = await AsyncStorage.getItem('token_id');
        try {
            await axios({
                method: 'get',
                url: Chat_Details + `/${route?.params?.chatID}`,
                headers: {
                    Accept: "application/json",
                    Authorization: `Bearer ${Localtoken}`
                }
            }).then(
                function (res) {
                    // console.log('================res chat detail', res.data.data)
                    if (res.data.success == true) {
                        let oldmsg = res.data.data

                        setMessages(oldmsg.reverse())
                        setLoader(false)
                    } else {
                        alert(res.data.message)
                        setLoader(false)
                    }
                }
            )
        } catch (error) {
            console.log('error chat details', error);
            setLoader(false)
        }
    }


    const onSend = () => {
        if (message == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'Please enter message'
            })
        } else {
            // setMessages(prev => prev.concat({ "message": message, 'type': 'text', 'sent_by': USER_ID }))
            setMessage('')
            let msg = {
                type: 'text',
                message: message,
                booking_id: route?.params?.Book_ID,
                sender_id: USER_ID,
                sent_by: USER_ID,
                receiver_id: USER_ID == route?.params?.sender_id ? route?.params?.receiver_id : route?.params?.sender_id,
                chatId: route?.params?.chatID,
                sent_by_detail:{name:route?.params?.sent_name}
            }

            socket.emit('sendMessage', msg);
            setMessages([msg, ...messages])

            console.log(msg)
        }
    }


    const renderItem = ({ item, index }) => {
        // console.log('================render item', item.sent_by);
        return (
            <View key={index}>
                <View style={{ alignSelf: item?.sent_by == USER_ID ? 'flex-end' : 'flex-start', margin: 8, backgroundColor: item?.sent_by == USER_ID ? '#f3f6f9' : '#FFEEDC', width: width / 2, borderRadius: 8, padding: 8 }}>

                    <Text style={{ marginHorizontal: 8, fontSize: 16, color: '#000', }}>{item?.message}</Text>

                    <View style={{ flexDirection: 'row', padding: 8, marginTop: 5 }}>
                        <Text style={{ fontSize: 12, color: '#000' }}>{item?.sent_by_detail?.name == null ? 'Shortlet' : item?.sent_by_detail?.name},</Text>
                        <Text style={{ fontSize: 12, color: '#000' }}>{moment(item?.updated_at).format('hh:mm:a')}</Text>
                    </View>
                </View>
            </View>
        )
    }

    return (
        <>
            <View style={{ flex: 1}}>
                {/* <Header Heading={'Peter Parker'} /> */}
                <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={{ flexDirection: 'row',
                 height: 90, 
                 
                 alignItems: "flex-end",
                }} >
                     {/* <StatusBar backgroundColor={'blue'} /> */}
                    <View style={{ flexDirection: "row", justifyContent: 'space-between', alignItems: 'center' }}>
                        <TouchableOpacity
                            onPress={() => props?.route?.params?.MyBooking == 'MyBooking' ? props?.navigation?.navigate('MyBooking') : props.navigation.navigate('Chat', { notificationChat: props.route.params.notificationChat })}
                        // onPress={}
                        >
                            <Image resizeMode="contain" source={Images.Chevron_Right} style={{
                                width: 20, height: 20, marginLeft: 20, marginBottom: 10
                            }} />
                        </TouchableOpacity>
                        {
                            route?.params?.chatImage == null ?
                            <Image source={ Images.GirlImageIcon} style={{ height: 32, width: 32, marginHorizontal: 12, marginBottom: 10, borderRadius: 20, }} />
                           : <Image source={{ uri: route?.params?.chatImage }} style={{ height: 32, width: 32, marginHorizontal: 12, marginBottom: 10, borderRadius: 20, }} />
                        }
                        <Text style={{ marginBottom: 15, textAlign: 'center', fontSize: 16, fontWeight: '500', color: color.appWhiteColor, alignSelf: 'center' }}>{route?.params?.chatName}</Text>
                    </View>
                </LinearGradient>
                <KeyboardAwareView>
                <View style={{
                    backgroundColor: '#FFF',
                    flex: 0.9
                }}>
                    {
                        loader ? <View><ActivityIndicator size={'large'} color='orange' /></View> :
                            <FlatList
                                // ref={chatListRef}
                                inverted={true}
                                data={messages}
                                renderItem={renderItem}
                                keyExtractor={(item, index) => index.toString()}
                                // style={{flex:1}}
                                extraData={messages}
                            // onContentSizeChange={handleScrollToEnd()}
                            />
                    }
                </View>
                <View style={{
                    // flex: 0.1, backgroundColor: '#fff'
                    flex: 0.10,
                    backgroundColor: '#F7F6FC',
                    flexDirection: 'row', justifyContent: 'flex-end', alignItems: 'center', paddingHorizontal: 12,
                }}>
                    <TextInput
                        placeholder='Write Here ...'
                        value={message}
                        onChangeText={(text) => setMessage(text)}
                        style={{
                            height: 55, width: '90%', paddingLeft: 12, color: '#000'
                            // backgroundColor: '#F7F6FC'
                        }} placeholderTextColor={color.appTextColor}
                        // multiline
                        caretHidden={true}

                    />
                    <TouchableOpacity onPress={() => onSend()} style={{ padding: 12 }} >
                        <Image source={Images.messageIcons} style={{ height: 24, width: 24 }} />
                    </TouchableOpacity>
                </View>
                </KeyboardAwareView>

                {/* <View style={{
                    // flex: 0.1, backgroundColor: '#fff'
                    flex: 0.1,
                    backgroundColor: '#F7F6FC',
                    // flexDirection: 'row', justifyContent: 'flex-end', alignItems: 'center', paddingHorizontal: 12
                }}>
                    <GiftedChat

                        messages={message}
                        onSend={() => storeMessages()}
                        user={{
                            id: USER_ID
                        }}
                        renderBubble={props => {
                            return <Bubble {...props} wrapperStyle={{ right: { backgroundColor: color.appOrangeColor, } }} />
                        }}
                        forceGetKeyboardHeight
                    // loadEarlier
                    // isAnimated
                    // keyboardShouldPersistTaps="never"

                    // showUserAvatar
                    />
                </View> */}
            </View>
        </>
    )
}

export default ChatMessage

const styles = StyleSheet.create({})