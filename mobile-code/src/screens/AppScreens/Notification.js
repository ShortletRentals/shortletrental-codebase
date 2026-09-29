
import {
  View,
  Text,
  Image,
  TouchableOpacity,
  Modal,
  Alert,
  SafeAreaView,
  Pressable,
  InteractionManager,
  StatusBar,
} from 'react-native';
import React, { useContext, useEffect } from 'react';
import { color, height, width } from '../../styles/colors';

import { SwipeListView } from 'react-native-swipe-list-view';

import Header from '../../components/Header';
import Images from '../../styles/Images';
import { useState } from 'react';
import LinearGradient from 'react-native-linear-gradient';
import axios from 'axios';
import {
  Notification_List,
  Notification_Read,
  Notification_Remove,
} from '../../network/Webconstant';
import { Context as AuthContext } from '../../context/AuthContext';
import moment from 'moment';
import { SliderShimmerNotification } from '../../components/Skeleton';
import { Toast } from 'react-native-toast-message/lib/src/Toast';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { useFocusEffect } from '@react-navigation/native';
import ReactNativeModal from 'react-native-modal';
import { BackHandler } from 'react-native';


export default function Notification(props) {
  const [modal, setModal] = useState(false);
  const [data, setData] = useState([]);
  const [loader, setLoader] = useState(false);
  const {
    checkActiveUser,
    state: { Token_ID, USER_ID, USERDATA },
  } = useContext(AuthContext);


  const handler = () => {
    props.navigation.navigate('Home')
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
  useFocusEffect(
    React.useCallback(() => {
      setData([])
      const task = InteractionManager.runAfterInteractions(async () => {
        // checkActiveUser().then(res => {
        //   if (res) {
        //     getNotificationListData();
        //   }
        // });
        const Localtoken = await AsyncStorage.getItem('token_id');
        console.log('=======token add', Localtoken);
        if (Localtoken == null) {
          Alert.alert('Hold on!', 'Please login first?', [
            {
              text: 'Cancel',
              onPress: () => null,
              style: 'cancel',
            },
            { text: 'LOGIN', onPress: () => props?.navigation?.navigate('Logins') },
          ]);
        } else {
          getNotificationListData()
        }
      });

      return () => task.cancel();
    }, [props]),
  );

  // console.log('-------------------------route',route.params.);
  const getNotificationListData = async () => {
    console.log('------------notification list ----------');
    setLoader(true);
    const Localtoken = await AsyncStorage.getItem('token_id');
    try {
      await axios({
        method: 'post',
        url: Notification_List,
        headers: {
          Authorization: `Bearer ${Localtoken}`,
          Accept: 'application/json',
        },
      }).then(function (response) {
        if (response.data.status == true) {
          // console.log('response notification', response.data.data);
          setData(response.data.data);
          setLoader(false);
        } else {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: response.data.message,
          });
          setLoader(false);
        }
      });
    } catch (error) {
      console.log('error ', error);
      setLoader(false);
    }
  };
  const deleteHandlerApi = async id => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    try {
      await axios({
        method: 'post',
        url: Notification_Remove,
        data: { notification_id: id },
        headers: {
          Authorization: `Bearer ${Localtoken}`,
          Accept: 'application/json',
        },
      }).then(function (response) {
        if (response.data.status == true) {
          console.log('response notification remove', response.data);
          setData([...data].filter(a => a.id !== id));
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message,
          });
        }
      });
    } catch (error) {
      console.log('error ', error);
    }
  };

  const removeAllApi = async () => {
    setModal(false);
    const Localtoken = await AsyncStorage.getItem('token_id');
    try {
      await axios({
        method: 'post',
        url: Notification_Remove,
        headers: {
          Authorization: `Bearer ${Localtoken}`,
          Accept: 'application/json',
        },
      }).then(function (res) {
        if (res.data.status == true) {
          console.log('res data', res.data);
          setData([]);
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: res.data.message,
          });
        }
      });
    } catch (error) {
      console.log('error is', error);
    }
  };

  const readNotificationHandlerApi = async id => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    try {
      await axios({
        method: 'post',
        url: Notification_Read,
        data: { notification_id: id },
        headers: {
          Authorization: `Bearer ${Localtoken}`,
          Accept: 'application/json',
        },
      }).then(function (response) {
        if (response.data.status == true) {
          // console.log('response read ', response.data.data);
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message,
          });
          getNotificationListData()
        } else {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: response.data.message,
          });
        }
      });
    } catch (error) {
      console.log('error read', error);
    }
  };
  const markAsReadAllApi = async () => {
    setModal(false);
    const Localtoken = await AsyncStorage.getItem('token_id');
    try {
      await axios({
        method: 'post',
        url: Notification_Read,
        headers: {
          Authorization: `Bearer ${Localtoken}`,
          Accept: 'application/json',
        },
      }).then(function (response) {
        if (response.data.status == true) {
          console.log('response read ', response.data);
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message,
          });
          getNotificationListData()
        } else {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: response.data.message,
          });
        }
      });
    } catch (error) {
      console.log('error read', error);
    }
  };

  const navigationHandler = (item) => {
    if (item?.item?.notification_for == 'Booking Reservation') {
      props?.navigation?.navigate('Payment', { ID: item.item.property_id, from: 'Notification', notificationID: item?.item?.id ,orderID:item?.item?.order_id})
    } else if (item?.item?.notification_for == 'Booking') {
      props?.navigation?.navigate('MyBooking', { mybooking: 'mybook' })
    } else if (item?.item?.notification_for == 'Chat') {
      props?.navigation?.navigate('Chat', { notificationChat: "Notify" })
    } else if (item?.item?.notification_for == 'Registration') {

    }
  }

  console.log('===========d',data)
  return (
    <View style={{ backgroundColor: '#F7F6FC', flex: 1 }}>
      <Header
        // isEdit props={props}
        Heading={'Notification'}
        onPress={() => props.navigation.navigate('Home')}
      />
      <ReactNativeModal
        isVisible={modal}
        style={{
          position: 'absolute',
          alignSelf: 'flex-end',
        }}
        animationIn="fadeIn"
        animationOut={'fadeOut'}
        onDismiss={() => setModal(false)}
        onBackdropPress={() => setModal(false)}>
        <View
          style={{
            backgroundColor: '#fff',
            height: 60,
            width: 155,
            alignSelf: 'flex-end',
            marginTop: 61,
            marginHorizontal: 20,
            borderWidth: 1,
            borderColor: '#e1e1e1',
            borderRadius: 8,
          }}>
          <TouchableOpacity onPress={() => removeAllApi()}>
            <Text
              style={{
                marginHorizontal: 12,
                color: '#000',
                marginTop: 5,
              }}>
              Clear All
            </Text>
          </TouchableOpacity>

          <TouchableOpacity onPress={() => markAsReadAllApi()}>
            <Text style={{ marginHorizontal: 12, color: '#000', marginTop: 5 }}>
              Mark As Read All
            </Text>
          </TouchableOpacity>
        </View>
      </ReactNativeModal>


      <TouchableOpacity
        onPress={() => setModal(true)}
        style={{ alignSelf: 'flex-end', top: -35, marginHorizontal: 20 }}>
        <Image
          source={Images.DotIcon}
          resizeMode="contain"
          style={{ height: 24, width: 24 }}
        />
      </TouchableOpacity>
      <View>
        {loader ? (
          <SliderShimmerNotification />
        ) : (
          data == '' || data == 'undefined' || data == [] ? 
             
              <View style={{ justifyContent: 'center', alignItems: 'center',marginTop:150
               }}>
                                    <Image source={Images.No_Data} style={{ height: 200, width: 200 }} />
                                    <Text style={{ alignSelf: 'center', color: '#000', fontSize: 20, justifyContent: 'center', }}>No Data Found</Text></View>                                 
                                :
          <View
            style={{
              backgroundColor: color.appTextBackgoundColor,
              marginTop: -22,
            }}>
            <SwipeListView
              data={data}
              renderItem={(data, rowMap) => (
                <Pressable
                  onPress={() => {
                    //   props.navigation.navigate('Booking', {
                    //     id: data.item.get_property.id,
                    //   })
                    // },
                    data?.item?.is_read == 0 ?
                      readNotificationHandlerApi(data.item.id) : '',
                      navigationHandler(data)

                  }}
                  style={{
                    backgroundColor:
                      data.item.is_read == 1
                        ? '#FFFFFF'
                        : '#E9E7F9',
                    marginTop: 15,
                    borderRadius: 10,
                    marginHorizontal: 24,
                    // height:102,
                    flexDirection: 'column', justifyContent: 'center', alignItems: 'flex-start'
                  }}>
                  <Text
                    style={{
                      marginHorizontal: 10,
                      // marginVertical: 6,
                      color: color.primaryColorBlack,
                      paddingHorizontal: 8,
                      fontSize: 15,
                      paddingTop: 16

                    }}>
                    {data?.item?.message}

                  </Text>
                  <Text
                    style={{
                      marginHorizontal: 10,
                      color: '#393939',
                      marginVertical: 6,
                      // marginBottom: 10,
                      paddingHorizontal: 8,
                      fontSize: 15,
                    }}>
                    {moment(data.item.created_at).format('DD-MM-YYYY')}
                  </Text>
                  {
                    data?.item?.notification_for == 'Chat' ? <Text style={{ color: '#000', marginBottom: 10, marginHorizontal: 15 }}>{data?.item?.title}</Text> : null
                  }
                </Pressable>
              )}
              ListFooterComponent={<View style={{ marginBottom: 150 }} />}
              renderHiddenItem={(data, rowMap) => (
                <TouchableOpacity
                  onPress={() => deleteHandlerApi(data.item.id)}
                  style={{
                    alignSelf: 'flex-end',
                    marginRight: 30,
                    justifyContent: 'center',
                    alignItems: 'center',
                    backgroundColor: '#F1592A',
                    height: 60,
                    width: 55,
                    marginTop: 15,
                    borderRadius: 12,
                    flex: 2,
                  }}>
                  <Image
                    source={Images.deleteSliderIcon}
                    style={{ flex: 1, height: 60, width: 40 }}
                    resizeMode="cover"
                  />
                  {/* <Text>Delete</Text> */}
                </TouchableOpacity>
              )}
              //    leftOpenValue={75}
              rightOpenValue={-75}
            />
          </View>
        )}
      </View>
      <View style={{ marginBottom: 100 }} />
    </View>
  );
}
