import React, { useState, useRef, useEffect } from 'react';
import {
  View,
  Image,
  Text,
  ImageBackground,
  TextInput,
  TouchableOpacity,
  _Text,
  ScrollView,
  FlatList,
  StyleSheet,
  ActivityIndicator,
  Pressable,
  InteractionManager,
  Modal,
  TouchableWithoutFeedback,
  Button,
  Alert,
} from 'react-native';
import { color, height, width } from '../../styles/colors';
import Images from '../../styles/Images';
import AsyncStorage from '@react-native-async-storage/async-storage';
import axios from 'axios';

import { commonStyles } from './../../styles/style';
import { Colors, colors } from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import CheckBox from '@react-native-community/checkbox';
import ActionSheet, { ActionSheetRef } from 'react-native-actions-sheet';
import {
  BASE_URL,
  HOME_URL,
  appTo_Favourite,
  add_To_Favourite,
  getBooking,
  Share_Booking,
  starRating,
} from '../../network/Webconstant';

import { SafeAreaView } from 'react-native-safe-area-context';
import StarRating from 'react-native-star-rating';
import { useContext } from 'react';
import { Context as BookingContext } from '../../context/BookingContext';
import { Context as AuthContext } from '../../context/AuthContext';
import { SliderShimmer, SliderShimmerMyBooking } from '../../components/Skeleton';
import { Toast } from 'react-native-toast-message/lib/src/Toast';
import { useFocusEffect, useNavigation } from '@react-navigation/native';
import CountryPicker from 'react-native-country-picker-modal'
import ReactNativeModal from 'react-native-modal';
import { BackHandler } from 'react-native';
import moment from 'moment';
// import Modal from 'react-native-modal'
let myNewID = ''
const renderData = [
  {
    image: Images.calenderIcon,
    title: '#ICICICLUUXURY',
    Hotelname: 'DOM HOTELS',
    Description: 'Grab Flat 15% OFF on 3,4 &5-star in India',
    Name: 'treat yourself to a refreshing.',
  },
  {
    image: Images.House,
    title: '#PARIS',
    Hotelname: 'HOLIDAYS',
    Description: 'Explore the Beauty of paris with Up to 20% Off',
    Name: 'Fun-Filled activities',
  },
  {
    image: Images.fbIcon,
    title: '#ICICICLUUXURY',
    Hotelname: 'DOM HOTELS',
    Description: 'Grab Flat 15% OFF on 3,4 &5-star in India',
    Name: 'treat yourself to a refreshing.',
  },
  {
    image: Images.fbIcon,
    title: '#ICICICLUUXURY',
    Hotelname: 'HOLIDAYS',
    Description: 'Explore the Beauty of paris with Up to 20% Off',
    Name: 'Fun-Filled activities',
  },
];
const Data = [
  {
    id: 'Not-confirmed-by-Host',
    title: 'All Bookings',
  },
  {
    id: 'desc',
    title: 'New',
  },
  {
    id: 'Completed-Booking',
    title: 'Completed',
  },
  {
    id: 'Cancelled-Booking',
    title: 'Cancelled',
  },
];

let myToken = '';
let ratingID = '';
const MyBooking = props => {
  const [starCount, setStarCount] = useState(1);
  const onStarRatingPress = rating => {
    setStarCount(rating);
  };
  const actionSheetRef = useRef(null);
  const actionSheetShare = useRef(null)
  const [isSelect, setIsSelect] = useState(0);
  const [myBooking, setMyBooking] = useState([]);
  const [curIndex, setCurIndex] = useState(0);

  const [loader, setLoader] = useState(false);
  const [ratingLoader, setRatingLoader] = useState(false)
  const navigation = useNavigation()
  const [visible, setVisible] = useState(false)
  const [isVisible, setIsVisible] = useState(false)
  const [country, setCountry] = useState('234')
  const [countryCode, setCountryCode] = useState('NG')
  const [mobileNumber, setMobileNumber] = useState('')
  const [shareLoader, setShareLoader] = useState(false)
  const [reviewText, setReviewText] = useState('')

  const {
    cancelBookingApi,

  } = useContext(BookingContext);

  useEffect(() => {
    let myBookings = async () => {
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

        getMyBookingApi('','')
        setIsVisible(false)
        setCurIndex(0)

      }
    }
    myBookings()
  }, [props])

  // console.log('------------------------ddddddddddddddd---------------',props.route.params.mybooking);


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
    }, [props]))


  const onPressHandler = index => {
    // console.log('inddd', index)
    if (index == 0) {
      setCurIndex(0);
      getMyBookingApi('','');
    } else if (index == 1) {
      setCurIndex(1);
      getMyBookingApi("Not-confirmed-by-Host","desc");
    } else if (index == 2) {
      setCurIndex(2);
      getMyBookingApi('Completed-Booking','');
    } else if (index == 3) {
      setCurIndex(3);
      getMyBookingApi('Cancelled-Booking','');
    }
  };
  const getMyBookingApi = async (queryParams,id) => {
    console.log('==========api call time-----------');
    setLoader(true);
    const Localtoken = await AsyncStorage.getItem('token_id');

    try {
      await axios({
        method: 'get',
        url: `${getBooking}?status=${queryParams}&order=${id}`,
        // data,
        headers: {
          "Authorization": `Bearer ${Localtoken}`,
          "Accept": 'application/json',
        },
      })
        .then(function (response) {
          if (response.data.status == true) {
            setLoader(false);
            setMyBooking(response.data.data);
            // console.log('response booking', response.data.data);
          } else {
            setLoader(false);
            Toast.show({
              type: 'error',
              text1: 'Shortlet',
              text2: response.data.message,
            });
          }
        })

    } catch (error) {
      console.log('error', error);
      setLoader(false)
      //   dispatch({ type: "loadActivityIndicator" })
    }
  };

  // console.log('====book', myBooking)
  const renderItem = ({ item, index }) => {
    return (
      <TouchableOpacity
        onPress={() => {
          setMyBooking([])
          // setLoader(true)
          onPressHandler(index)

        }}
        style={[
          styles.flatView,
          {
            backgroundColor:
              curIndex !== index ? color.white : color.appYellowColor,
          },
        ]}>
        <Text style={{ color: curIndex !== index ? 'black' : 'white' }}>
          {item.title}
        </Text>
      </TouchableOpacity>
    );
    // if (index == 0) {
    //   return (
    //     <TouchableOpacity
    //       onPress={() => {
    //         setLoader(true)
    //         setIsSelect(index)
    //         getMyBookingApi('');
    //       }}
    //       style={[
    //         styles.flatView,
    //         {
    //           backgroundColor:
    //             isSelect !== index ? color.white : color.appYellowColor,
    //         },
    //       ]}>
    //       <Text style={{ color: isSelect !== index ? 'black' : 'white' }}>
    //         {item.title}
    //       </Text>
    //     </TouchableOpacity>
    //   );
    // } else if (index == 1) {
    //   return (
    //     <TouchableOpacity
    //       onPress={() => {
    //         setLoader(true)
    //         setIsSelect(index)
    //         setMyBooking([])
    //         getMyBookingApi("Not-confirmed-by-Host");
    //       }}
    //       style={[
    //         styles.flatView,
    //         {
    //           backgroundColor:
    //             isSelect !== index ? color.white : color.appYellowColor,
    //         },
    //       ]}>
    //       <Text style={{ color: isSelect !== index ? 'black' : 'white' }}>
    //         {item.title}
    //       </Text>
    //     </TouchableOpacity>
    //   );
    // } else if (index == 2) {
    //   return (
    //     <TouchableOpacity
    //       onPress={() => {
    //         setLoader(true)
    //         setIsSelect(index)
    //         setMyBooking([])
    //         getMyBookingApi('Completed-Booking');
    //       }}
    //       style={[
    //         styles.flatView,
    //         {
    //           backgroundColor:
    //             isSelect !== index ? color.white : color.appYellowColor,
    //         },
    //       ]}>
    //       <Text style={{ color: isSelect !== index ? 'black' : 'white' }}>
    //         {item.title}
    //       </Text>
    //     </TouchableOpacity>
    //   );
    // } else if (index == 3) {
    //   return (
    //     <TouchableOpacity
    //       onPress={() => {
    //         setLoader(true)
    //         setIsSelect(index)
    //         setMyBooking([])
    //         getMyBookingApi('Cancelled-Booking');
    //       }}
    //       style={[
    //         styles.flatView,
    //         {
    //           backgroundColor:
    //             isSelect !== index ? color.white : color.appYellowColor,
    //         },
    //       ]}>
    //       <Text style={{ color: isSelect !== index ? 'black' : 'white' }}>
    //         {item.title}
    //       </Text>
    //     </TouchableOpacity>
    //   );
    // }
  };




  const starRatingApiHandler = async (item) => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    setRatingLoader(true)
    try {
      axios({
        method: 'post',
        url: starRating,
        data: { order_id: item, star: starCount, review: reviewText },
        headers: { Authorization: `Bearer ${Localtoken}` }
      }).then(function (res) {
        if (res.data.status == true) {
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: res.data.message
          })
          actionSheetRef.current.hide()
          getMyBookingApi('','')
          setCurIndex(0)
          setRatingLoader(false)
          setReviewText('')
          setStarCount(0)
        } else {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: res.data.message
          })
          setRatingLoader(false)
          actionSheetRef.current.hide()
        }
      });
    } catch (error) {
      console.log('errorr is ', error);
      setRatingLoader(false)
    }

  }
  const onSelect = (country) => {
    setCountryCode(country.cca2)
    setCountry(country.callingCode[0])
  }

  const onHandlerShareBookingApi = async () => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    // console.log('=======mobile number', mobileNumber);
    setShareLoader(true)
    try {
      axios({
        method: 'post',
        url: Share_Booking,
        data: {
          booking_id: myNewID,
          country_code: countryCode,
          mobile: mobileNumber
        },
        headers: { Accept: 'application/json', Authorization: `Bearer ${Localtoken}` }
      }).then(
        function (res) {
          // console.log('======share res', res);
          if (res.data.status) {
            Toast.show({
              type: 'success',
              text1: 'Shortlet',
              text2: res.data.message
            })
            setShareLoader(false)
            setIsVisible(false)
            setMobileNumber('')
            // setIsVisible(false)
          } else {
            Toast.show({
              type: 'error',
              text1: 'Shortlet',
              text2: res.data.message
            })
            // alert(res.data.message)
            setShareLoader(false)
          }
        }
      )
    } catch (error) {
      console.log('share error', error);
      setShareLoader(false)
    }
  }
  const renderBookingItem = ({ item, index }) => {
// console.log('--=======================item',item.get_property.avg_rating)
    return (
      <>
        <Pressable
        //  onPress={() => props.navigation.navigate('BookingDetails', {
        //   bookID: item.id,
        // })}
          style={{
            // height: 213,
            // width: 335,
            marginTop: 5,
            marginBottom: 10,
            marginHorizontal: 20,
            backgroundColor: color.appWhiteColor,
            borderColor: color.inputBoxBorderGray,
            borderWidth: 1,
            // alignSelf: 'center',
            top: 10,
            borderRadius: 10,
            paddingVertical:10
          }}>
          <Image source={{ uri: item?.get_property?.image }} style={{ height: 200, width: width-80, borderRadius: 12, marginHorizontal: 16,marginVertical:10 }} />
          <Text
             numberOfLines={1} style={{ width: width-80,marginHorizontal:16 , color: color.primaryColorBlack, fontSize: 14, fontWeight: '600' }}>
              {item?.get_property?.title}
            </Text>
          <View style={{ marginVertical: 10, marginHorizontal: 16 }}>
            <View
              style={{
                flexDirection: 'row',
                justifyContent: 'flex-start',
                alignItems: 'center',
              }}>
                 <Text
                         onPress={()=>props.navigation.navigate('ChatMessage',{ MyBooking :'MyBooking' ,chatID:item?.chat_id,Book_ID:item.id,
                         sender_id:1,//item?.host_id,
                         receiver_id:item?.guest_id,chatName:item.personal_first_name})}
                          style={styles.hostTxt}>Host - {item?.get_property?.get_host_details[0]?.name}</Text>
              <Text
                style={{
                  color: color.primaryColorBlack,
                  fontSize: 15,
                  fontWeight: '600',
                  marginLeft:10,
                  width: width / 2 - 60,
                }}>
                {item.booking_id}
              </Text>
                
                </View>
                <View style={{flexDirection:"row",justifyContent:'flex-start',alignItems:"center",marginVertical:5}}>
               
              <View>
                {
                  item.booking_status == 'Cancelled-Booking' ? null :
                    <TouchableOpacity onPress={() => [
                      setIsVisible(true), myNewID = item.id]
                      // actionSheetShare.current.show()
                    }
                      style={{
                        alignItems: 'center',
                        justifyContent: 'center',
                        borderRadius: 10,
                        borderColor: color.appOrangeColor,
                        borderWidth: 1,
                        paddingVertical: 2,
                        marginVertical: 2,
                        backgroundColor: color.appOrangeColor,
                        width: 100,
                        alignSelf: 'flex-end',
                        // marginHorizontal: 8
                      }}>
                      <Text
                        style={{
                          // width: width / 2 - 20,
                          textAlign: 'center',
                          fontSize: 14,
                          color: '#fff',
                          paddingHorizontal: 8
                        }}>
                        Share
                      </Text>
                    </TouchableOpacity>
                }

               
               
              </View>
              <View
                  style={{
                    alignItems: 'center',
                    justifyContent: 'center',
                    // borderRadius: 10,
                    borderColor: color.appTextBackgoundColor,
                    borderWidth: 1,
                    paddingVertical: 2,
                    width: width - 200,
                    backgroundColor: color.inputBoxBorderGray,
                    marginHorizontal:item.booking_status == 'Cancelled-Booking' ? 0 : 20,
                    // paddingHorizontal: 20,
                    borderRadius: 20,
                    padding: 10
                    // marginLeft: -30
                  }}>

                  <Text
                    style={{
                      textAlign: 'center',
                      fontSize: 14,
                      color: '#707070',
                      paddingHorizontal: 8
                    }}>
                    {item?.booking_status}
                  </Text>
                </View>
              </View>
              <View style={styles.host1}>
                        <Text>NGN {item?.per_night_price} X {item?.total_days} =</Text>
                        <Text style={styles.total}>NGN {Number(item?.per_night_price * item?.total_days)}</Text>
                    </View>
                    <View style={styles.host1}>
                        <Text style={{ color: color.primaryColorBlack }}>Caution Fee</Text>
                        <Text style={{ color: color.primaryColorBlack, fontSize: 15, fontWeight: '600' }}>NGN {item?.get_property?.security_deposit_amount}</Text>
                    </View>
                    <View
                        style={{ borderWidth: .5, borderColor: color.inputBoxBorderGray, marginVertical: 5, marginHorizontal: 0 }}
                    />
                    <View style={styles.host1}>
                        <Text style={{}}>Grand Total</Text>
                        <Text style={{ color: color.primaryColorBlack, fontSize: 15, fontWeight: '600' }}>NGN {Number(item?.per_night_price * item?.total_days) + Number(item?.get_property?.security_deposit_amount)}</Text>
                    </View>
                    <View
                        style={{ borderWidth: .5, borderColor: color.inputBoxBorderGray, marginVertical: 5, marginHorizontal: 0 }}
                    />
                    <View style={{ marginHorizontal: 0, flexDirection: 'row',width:'100%' }}>
                        <View style={{ marginRight: 5,width:'40%' }}>
                            <Text style={{ fontSize: 10, color: color.primaryColorBlack }}>
                                Check-in - Check out
                            </Text>
                            <Text style={{ fontSize: 10 }}>
                                {moment(item?.from_date).format('MMM DD, yyyy')} / {moment(item?.to_date).format('MMM DD, yyyy')}
                            </Text>
                        </View>
                        <View
                            style={{ height: 30, width: 0.5, backgroundColor: 'gray' }}></View>
                        <View style={{ marginHorizontal: 10 ,width:'15%'}}>
                            <Text style={{ fontSize: 10, color: color.primaryColorBlack }}>
                                Who
                            </Text>
                            <Text style={{ fontSize: 10 }}>
                                {item?.no_of_adult_guest + item?.
                                    no_of_children_guest + item?.
                                        no_of_babies_guest + item?.
                                        no_of_pet} Guests
                            </Text>
                        </View>
                        <View
                            style={{ height: 30, width: 0.5, backgroundColor: 'gray' }}></View>
                        <View style={{ marginLeft: 5,width:'40%' }}>
                            <Text style={{ fontSize: 10, color: color.primaryColorBlack }}>
                                Reservation Date & Time
                            </Text>
                            <Text style={{ fontSize: 10 }}>
                                {moment(item?.created_at).format('MMM DD, yyyy')} / {moment(item?.created_at).format('hh:mm:a')}
                            </Text>
                        </View>
                    </View>

                    <View style={{marginVertical:10}}>
                      <Text style={{ fontSize: 10, color: color.primaryColorBlack }}>Loyalty Points Earned</Text>
                      <Text>{item?.new_loyalty_amount == null ? '0' : item?.new_loyalty_amount}</Text>
                    </View>
            {/* <View
              style={{
                flexDirection: 'row',
                justifyContent: 'flex-start',
                alignItems: 'center', marginVertical: 5
              }}>
              <Image
                source={Images.BlueDotIcons}
                style={{ height: 8, width: 8 }}
              />
              <Text style={{ color: color.appTextColor, marginHorizontal: 10, fontSize: 14 }}>
                {item?.get_property?.type}
              </Text>
            </View> */}
            {/* <View
              style={{
                flexDirection: 'row',
                justifyContent: 'space-between',
                marginTop: 8,
                // marginHorizontal:10
              }}>
              <Text style={{ color: color.appTextColor, fontWeight: 'bold', width: width / 2 }}>
                NGN {item?.per_night_price} X {item?.total_days} nights
              </Text>
              <Text
                style={{ fontWeight: 'bold', color: color.primaryColorBlack, width: width / 2 }}>
                NGN {item?.per_night_price * item?.total_days}.00
              </Text>
            </View> */}

            <View
              style={{
                flexDirection: 'row',
                justifyContent: 'space-between',
                alignItems: 'center',
                marginTop: 8,
                marginBottom: 8,
              }}>
{
  item.booking_status == "Cancelled-Booking" ?

              <TouchableOpacity
                onPress={() =>
                  // props.navigation.navigate('BookingDetails', {
                  //   bookID: item.id,
                  // })
                  props.navigation.navigate('HomeDetails', {
                    propertyid: item.property_id, My_Book: 'MY_BOOK'
                  })
                }>
                <View
                  style={{
                    alignItems: 'center',
                    justifyContent: 'center',
                    width: 135,
                    height: 50,
                    borderRadius: width * (8 / 375),
                    borderWidth: 2,
                    borderColor: color.appOrangeColor,
                    backgroundColor: color.appLightOrangeColor,
                  }}>
                  <Text
                    style={{
                      alignSelf: 'center',
                      marginHorizontal: width * (30 / 375),
                      color: color.appOrangeColor,
                    }}>
                    Re-Book

                  </Text>
                </View>
              </TouchableOpacity>
              : null}
              {/* {
                            item.booking_status == "Cancelled-Booking" ?
                                <View style={{ alignItems: 'center', justifyContent: 'center', width: 142, height: 50, borderRadius: width * (8 / 375), borderWidth: 2, borderColor: color.appOrangeColor, backgroundColor: color.appLightOrangeColor }}>
                                    <TouchableOpacity onPress={() => props.navigation.navigate('BookingDetails', { bookID: item.id })}>
                                        <Text style={{ alignSelf: 'center', marginHorizontal: width * (40 / 375), color: color.appOrangeColor }}>
                                            Re-Book
                                        </Text>
                                    </TouchableOpacity>
                                </View> : null

                            // <TouchableOpacity onPress={() => actionSheetRef.current?.show()}>
                            //     <View style={{ width: 142, height: 50, borderRadius: width * (8 / 375), borderWidth: 2, borderColor: color.appOrangeColor, backgroundColor: color.appLightOrangeColor, justifyContent: 'center', alignItems: 'center' }}>
                            //         <Text style={{ marginHorizontal: width * (40 / 375), color: color.appOrangeColor }}>
                            //             Rate Now

                            //         </Text>
                            //     </View>
                            // </TouchableOpacity>
                        } */}
              {
                item?.booking_status == 'Not-confirmed-by-Host' ? (
                  <TouchableOpacity
                    onPress={async () => {
                      const Localtoken = await AsyncStorage.getItem('token_id')
                      let headers = { Authorization: `Bearer ${Localtoken}` };
                      cancelBookingApi({ order_id: item.id }, headers, () => {
                        getMyBookingApi();
                      })
                    }
                    }
                  // onPress={() => props.navigation.navigate('BookingDetails', { bookID: item.id })}
                  >
                    <View
                      style={{
                        alignItems: 'center',
                        justifyContent: 'center',
                        width: 135,
                        height: 50,
                        borderRadius: width * (8 / 375),
                        borderWidth: 2,
                        borderColor: color.appOrangeColor,
                        backgroundColor: color.appLightOrangeColor,
                      }}>

                      <Text
                        style={{
                          alignSelf: 'center',
                          marginHorizontal: width * (30 / 375),
                          color: color.appOrangeColor,
                        }}>
                        Cancel
                      </Text>
                    </View>
                  </TouchableOpacity>
                ) : null
                // <TouchableOpacity onPress={() => actionSheetRef.current?.show()}>
                //     <View style={{ width: 142, height: 50, borderRadius: width * (8 / 375), borderWidth: 2, borderColor: color.appOrangeColor, backgroundColor: color.appLightOrangeColor, justifyContent: 'center', alignItems: 'center' }}>
                //         <Text style={{ marginHorizontal: width * (40 / 375), color: color.appOrangeColor }}>
                //             Cancel

                //         </Text>
                //     </View>
                // </TouchableOpacity>
              }
              {
                item?.booking_status == 'Completed-Booking' && item.get_property.avg_rating < 1 ? (

                  <TouchableOpacity
                    // onPress={() =>
                    //   cancelBookingApi({ order_id: item.id }, headers, () => {
                    //     getMyBookingApi();
                    //   })
                    // }
                    onPress={() =>{ actionSheetRef.current.show()
                      

                    }}
                  >
                    <View
                      style={{
                        alignItems: 'center',
                        justifyContent: 'center',
                        width: 142,
                        height: 50,
                        borderRadius: width * (8 / 375),
                        borderWidth: 2,
                        borderColor: color.appOrangeColor,
                        backgroundColor: color.appLightOrangeColor,
                      }}>
                      <Text
                        style={{
                          alignSelf: 'center',
                          marginHorizontal: width * (30 / 375),
                          color: color.appOrangeColor,
                        }}>
                        {/* Cancel */}
                        Rate-Now
                      </Text>
                    </View>
                  </TouchableOpacity>
                ) : null
                // <TouchableOpacity onPress={() => actionSheetRef.current?.show()}>
                //     <View style={{ width: 142, height: 50, borderRadius: width * (8 / 375), borderWidth: 2, borderColor: color.appOrangeColor, backgroundColor: color.appLightOrangeColor, justifyContent: 'center', alignItems: 'center' }}>
                //         <Text style={{ marginHorizontal: width * (40 / 375), color: color.appOrangeColor }}>
                //             Cancel

                //         </Text>
                //     </View>
                // </TouchableOpacity>
              }
            </View>
            <ActionSheet ref={actionSheetRef}>
              <SafeAreaView>
                <View style={{ marginTop: 8, marginHorizontal: 15 }}>
                  <View
                    style={{
                      borderBottomColor: color.appTextBackgoundColor,
                      borderBottomWidth: 1,
                      marginBottom: 20,
                    }}>
                    <View
                      style={{
                        paddingVertical: 10,
                        borderBottomWidth: 1,
                        borderBottomColor: color.appTextBackgoundColor,
                      }}>
                      <Text
                        style={{
                          fontSize: 20,
                          fontWeight: '500',
                          marginBottom: 10,
                          color: color.primaryColorBlack,
                        }}>
                        Give Review and Ratings

                      </Text>
                    </View>

                    <View
                      style={{
                        marginTop: 25,
                        marginBottom: 20,
                        justifyContent: 'center',
                      }}>
                      <StarRating
                        disabled={false}
                        emptyStar={Images.emptyStarImage}
                        fullStar={Images.StartBigIcon}
                        // halfStar={'ios-star-half'}
                        // iconSet={Images.selectedIcons}
                        emptyStarColor={'#C1C1C1'}
                        // fullStarColor={'red'}
                        maxStars={5}
                        rating={starCount}
                        selectedStar={rating => onStarRatingPress(rating)}
                        starStyle={{ marginHorizontal: 12 }}
                        starSize={40}
                        containerStyle={{
                          marginHorizontal: 12,
                          alignSelf: 'flex-start',
                          margin: 8,
                          marginBottom: 14,
                        }}
                      />
                    </View>
                    <View>
                      <TextInput
                        placeholder='Write Your Reviews'
                        placeholderTextColor={'#000'}
                        value={reviewText}
                        onChangeText={(text) => setReviewText(text)}
                        multiline
                        style={{
                          height: 150,
                          margin: 10,
                          backgroundColor: color.appTextBackgoundColor,
                          borderRadius: 10,
                          marginLeft: 20,
                          marginRight: 20,
                          fontSize: 15, paddingHorizontal: 12, color: '#000'
                        }}
                        textAlignVertical='top'
                      />
                    </View>
                    <TouchableOpacity
                      onPress={() => starRatingApiHandler(item.id)}
                      style={{
                        marginTop: 20,
                        backgroundColor: color.appOrangeColor,
                        width: width - 30,
                        padding: 10,
                        borderRadius: 10,
                        justifyContent: 'center',
                        alignSelf: 'center',
                        height: 50,
                      }}>
                      <View
                        style={{
                          position: 'absolute',
                          flex: 1,
                        }}>
                        <Image
                          source={Images.whiteDot}
                          style={{
                            paddingLeft: 70,
                            width: 25,
                            height: 25,
                            resizeMode: 'contain',
                          }}
                        />
                      </View>
                      <View style={{ flexDirection: 'row', alignItems: 'center', justifyContent: 'center' }}>
                        <Text
                          style={{
                            textAlign: 'center',
                            fontSize: 15,
                            color: color.appWhiteColor,
                          }}>
                          Save
                        </Text>
                        <View style={{ marginLeft: 20 }}>
                          {
                            ratingLoader ? <ActivityIndicator size={'small'} color='#fff' /> : null
                          }
                        </View>
                      </View>
                    </TouchableOpacity>
                  </View>
                </View>
              </SafeAreaView>
            </ActionSheet>

          </View>
        </Pressable>

      </>
    );
  };
  return (
    <View
      style={[
        commonStyles.container,
        { backgroundColor: color.appTextBackgoundColor },
      ]}>
      <Header
        props={props}
        Heading={'My Bookings'}
        onPress={() => props.navigation.navigate('Home')}
      />

      <View>
        <View style={{}}>
          <FlatList
            data={Data}
            horizontal
            showsHorizontalScrollIndicator={false}
            renderItem={renderItem}
            keyExtractor={(item, index) => index.toString()}
          />
        </View>
        {loader ? <SliderShimmerMyBooking /> :

          myBooking.length == 0 ? <View style={{ alignSelf: 'center', marginTop: height / 4.5 }}>
            <Image source={Images.No_Data} style={{ height: 200, width: 200 }} />

            <Text style={{ alignSelf: 'center' }}>No Data Found</Text>
          </View>
            :
            (<View>
              <FlatList
                data={myBooking}
                renderItem={renderBookingItem}
                keyExtractor={(item, index) => index.toString()}
                showsVerticalScrollIndicator={false}
                ListFooterComponent={() => {
                  return <View style={{ marginVertical: 100 }} />;
                }}
              />
            </View>)
        }


      </View>


      <Modal

        //  isVisible={isVisible}
        visible={isVisible}
        onRequestClose={() => setIsVisible(false)}
        transparent={true}
        onDismiss={() => setIsVisible(false)}




      >
        <Pressable onPress={() => setIsVisible(false)} style={{ flex: 0.9, marginTop: '20%', backgroundColor: "#00000070" }}>
          <View style={{ backgroundColor: '#fff', marginTop: '40%', borderRadius: 10, borderWidth: 1, marginHorizontal: 16,borderColor:'#f3f6f9' }}>
            <TouchableOpacity style={{ marginTop: 10, marginHorizontal: 20 }}
              //onPress={()=>actionSheetShare.current.hide()}
              onPress={() => setIsVisible(false)}
            >
              {/* //actionSheetShare?.current?.hide() */}
              <Image source={Images.crossIcon} alignSelf='flex-end' style={{ height: 24, width: 24 }} />
            </TouchableOpacity>
            <Text style={{ fontSize: 24, color: '#000', marginHorizontal: 20 }}>Share Booking</Text>
            <View style={{ flexDirection: 'row', justifyContent: 'flex-start', alignItems: 'center', marginTop: 20, marginHorizontal: 20, }}>
             <View style={{height:50, flexDirection:"row",borderWidth:1,borderColor:color.inputBoxBorderGray,padding:10,borderRadius:12,alignItems:"center"}}>
              <CountryPicker
                {...{
                  onSelect,
                }}
                visible={visible}
                countryCode={countryCode}
                withFilter={true}


              />
              <Text style={{ color: '#000' }}>+{country}</Text>
              </View>
              <TextInput
                placeholder={'Mobile Number'}
                value={mobileNumber}
                onChangeText={text => {
                  setMobileNumber(text.replace(/[^0-9]/g, ''))
                }}
                maxLength={10}
                keyboardType='numeric'
                style={{
                  height: 50,
                  width: '60%',
                  margin: 10,
                  backgroundColor: color.appTextBackgoundColor,
                  borderRadius: 10,
                  paddingHorizontal: 14,
                  fontSize: 15, color: '#000'
                }}
                placeholderTextColor={color.appTextColor}


              />

            </View>
            <TouchableOpacity activeOpacity={0.5} onPress={() => onHandlerShareBookingApi()} style={{
              alignItems: 'center',
              justifyContent: 'center',
              borderRadius: 10,
              borderColor: color.appOrangeColor,
              borderWidth: 1,
              paddingVertical: 2,
              marginVertical: 2,
              backgroundColor: color.appOrangeColor,
              width: 100,
              height: 50,
              marginVertical: 20,
              marginHorizontal: 20,
              flexDirection: 'row'
            }}>
              <Text style={{ fontSize: 14, color: '#fff' }}>Share</Text>
              {
                shareLoader ? <View style={{}}><ActivityIndicator size={'small'} color='#fff' /></View> : null
              }
            </TouchableOpacity>
          </View>
        </Pressable>
      </Modal>
    </View>
  );
};

const styles = StyleSheet.create({
  flatView: {
    height: 40,
    backgroundColor: 'red',
    marginHorizontal: 6,
    justifyContent: 'center',
    alignItems: 'center',
    borderRadius: 10,
    paddingHorizontal: 12,
    marginTop: 10,
  },
  host1: {
    flexDirection: "row",
    // marginHorizontal: 20,
    marginVertical: 5,
    justifyContent: "space-between",
    alignItems: "center"
},
});
export default MyBooking;
