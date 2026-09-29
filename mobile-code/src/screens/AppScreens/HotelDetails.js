import React, { useState, useEffect, useRef } from 'react';
import {
  View,
  Image,
  Text,
  ImageBackground,
  StyleSheet,
  TouchableOpacity,
  _Text,
  ScrollView,
  FlatList,
  Share,
  ActivityIndicator,
  StatusBar,
  useWindowDimensions,
  InteractionManager,
  Modal,
  Platform,
} from 'react-native';
import { color, height, width } from '../../styles/colors';
import Images from '../../styles/Images';
import { commonStyles } from './../../styles/style';
import { Colors, colors } from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import { Context as AuthContext } from '../../context/AuthContext';
import { Toast } from 'react-native-toast-message/lib/src/Toast';

import { SliderBox } from 'react-native-image-slider-box';
import LinearGradient from 'react-native-linear-gradient';
import axios from 'axios';
import {
  AddCartBooking,
  Home_Details,
  MAIN_URL,
  add_To_Favourite,
  propertyBookingCalender,
} from '../../network/Webconstant';
import {
  useFocusEffect,
  useIsFocused,
  useNavigation,
  useRoute,
} from '@react-navigation/native';
import Carousel, { Pagination } from 'react-native-snap-carousel';
import { Context as HomeContext } from '../../context/HomeContext';
import { useContext } from 'react';
import { Svg, SvgUri } from 'react-native-svg';
import {
  SliderShimmer,
  SliderShimmerHomeDetail,
} from '../../components/Skeleton';
import FastImage from 'react-native-fast-image';
import AsyncStorage from '@react-native-async-storage/async-storage';
import RenderHTML from 'react-native-render-html';
import { Alert } from 'react-native';
import { Calendar } from 'react-native-calendars';
// import YouTube, { YouTubeStandaloneAndroid } from 'react-native-youtube';
import MapView, { Marker } from 'react-native-maps';
import { Linking } from 'react-native';
import { BackHandler } from 'react-native';
import ImageViewer from 'react-native-image-zoom-viewer';
import YoutubePlayer from "react-native-youtube-iframe";
import moment from 'moment';

state = {
  images: [
    'https://images.pexels.com/photos/7019026/pexels-photo-7019026.jpeg?auto=compress&cs=tinysrgb&h=750&w=1260',
    'https://th.bing.com/th/id/OIP.NWIv-LGdhuWLl32-m-_g-gHaE7?pid=ImgDet&w=1280&h=853&rs=1',
    'https://www.familyvacationcritic.com/wp-content/uploads/sites/19/2015/11/standard-king-room-v1970094-90.jpg',
    'https://th.bing.com/th/id/OIP.OIizfpjCijShFNN6NoQMAwHaEK?pid=ImgDet&rs=1',
  ],
};
const Data = [
  {
    id: 1,
    title: 'FEATURES',
  },
  {
    id: 2,
    title: 'DESCRIPTION',
  },
  {
    id: 3,
    title: 'SPECIAL FEATURES',
  },
  {
    id: 4,
    title: 'OPTIONAL SERVICES',
  },
  {
    id: 5,
    title: 'YOUR SCHEDULE',
  },
  {
    id: 6,
    title: 'WATCH THE VIDEO',
  },
  {
    id: 7,
    title: 'THINGS TO KNOW',
  },
  {
    id: 8,
    title: 'MAP AND DISTANCES',
  },
];
let data = [];
// let propId =""
let calenderID = '';
let  fromDate = ''
let endDate =  ''
const HomeDetails = props => {
  const navigation = useNavigation();
  const { propertyid, MY_FAV } = props?.route?.params;
  // const { propertyid, price } = props?.route?.params
  const [select, setSelect] = useState(false);
  const [curIndex, setCurIndex] = useState(0);
  const [loader, setLoader] = useState(false);
  const [details, setDetails] = useState('');
  const [bookLoader,setBookLoader] = useState(false)
  const [images, setImages] = useState([]);
  const [zoomImages, setZoomImages] = useState([]);

  const [amenity, setAmenity] = useState('');
  const [modalVisible, setModalVisible] = useState(false);
  const [modalVisibleDate, setModalVisibleDate] = useState(false);
  const [modalImage, setModalImage] = useState('')

  const [date, setDate] = useState(new Date());
  const [dateLocal, setDateLocal] = useState(moment(new Date()).clone().add(1, "days").format('YYYY-MM-DD'));

  const [dateTrue, setDateTrue] = useState(false)
  const [dateLocalTrue, setDateLocalTrue] = useState(false)
  const [homeDetailsExtraServices, setHomeDetailsExtraServices] = useState([]);
  const [homeDetailsData, setHomeDetailsData] = useState([]);
  const [price, setPrice] = useState(0);
  const [moreFeature, setMoreFeature] = useState(false);
  const [calenderData, setCalenderData] = useState(null);
  const [page, setPage] = useState(0);
  const [count1, setCount1] = useState(0);
  const [count2, setCount2] = useState(0);
  const [count3, setCount3] = useState(0);
  const [count4, setCount4] = useState(0);
  const [modal, setModal] = useState(false);
  const [guestModal, setGuestModal] = useState(false);
  const [initialRegion, setInitialRegion] = useState(
    {
      latitude: 6.4406164,
      longitude: 3.4785239,
      latitudeDelta: 0.0922,
      longitudeDelta: 0.0421,
    }
  )
  let arr =
    details.video_url == null ? 'xEbEAK3Q2Lo' : details.video_url.split('=');
  // let title = details?.title.split('|')
  const { width } = useWindowDimensions();
  const source = {
    html: '<p>This apartment is beautifully furnished style apartment in the heart of the Lekki phase 1 . It is in close proximity to the entertainment and business districts of Victoria Island and Ikoyi alike. it is located in a serene gated estate which assures you of safety at all times. Modern conveniences include 24/7 hours electricity supported by generator, fitted kitchen, WIFI, Smart TVs amongst others. Everything you need for a great stay can be found at this location.</p>' || '<p></p>',
  };
  const _mapView = useRef(null)

  const counter1 = () => {
    if (count1 > 0) {
      setCount1(count1 - 1);
    }
  };
  const counter2 = () => {
    if (count2 > 0) {
      setCount2(count2 - 1);
    }
  };
  const counter3 = () => {
    if (count3 > 0) {
      setCount3(count3 - 1);
    }
  };
  const counter4 = () => {
    if (count4 > 0) {
      setCount4(count4 - 1);
    }
  };

  const onDayPress = day => {
    setModalVisibleDate(!modalVisibleDate);


    // setDateTrue(true) : setDateTrue(false)

    setDate(moment(day.dateString).format('YYYY-MM-DD'));
  };

  const onDayPressLocal = day1 => {
    setModal(!modal);


    setDateLocalTrue(true)

    setDateLocal(moment(day1.dateString).format('YYYY-MM-DD'))
  };
  useEffect(() => {
    // console.log('==============rrrrrrr', details.video_url);

    if (propertyid) {
      // console.log('===========details property id===========');

      setLoader(true);
      setDate(new Date())
      setDateLocal(moment(new Date()).clone().add(1, "days").format('YYYY-MM-DD'))
      setCount1(0)
      setCount2(0)
      setCount3(0)
      setCount4(0)
      onHandleHomeDetailsApi();
      getPrice();
      onHandlerCalenderBookingApi();
    }
  }, [propertyid, props]);

  const handler = () => {
    props?.route?.params?.My_Book == 'MY_BOOK'
      ? props?.navigation?.navigate('MyBooking') : MY_FAV == 'MY_FAV' ? props?.navigation?.navigate('Myfavorites')
        : props?.navigation?.navigate('Home')
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
    if (page) {
      // console.log('---------------page----------------');
      onHandlerCalenderBookingApi();
    }
  }, [page]);
  const getPrice = async () => {
    let PRICESTORAGE = await AsyncStorage.getItem('Price');
    setPrice(PRICESTORAGE);
    // console.log('===================local ', PRICESTORAGE, price);
  };

  let dayDifference = Math.floor(
    (new Date(moment(dateLocal).format('YYYY-MM-DD')).getTime() - new Date(moment(date).format('YYYY-MM-DD')).getTime()) /
    (1000 * 60 * 60 * 24),
  );
  // console.log('-------ddddddddddddddddddddddddddddddddddddddd', details?.price, MY_FAV);
  // const  onHandleHomeDetailsApi = () => {
  //     let data = { propertyid: route?.params?.propertyid }
  //     homeDetailsApi(data, () => {
  //     })
  // }
  const onHandleHomeDetailsApi = async () => {
    // console.log('-------------------home details api------------');
    const Localtoken = await AsyncStorage.getItem('token_id');

    axios({
      method: 'post',
      url: Home_Details,
      data: { propertyid },
      headers: {
        Accept: 'application/json',
        Authorization: `Bearer ${Localtoken}`,
      },
    })
      .then(response => {
        if (response.data.status == true) {
          // console.log("api response is =======",response.data.get_extra_service)
          // data=response.data
          if (response?.data?.get_property_images?.length) {
            let temp = response?.data?.get_property_images?.map(
              img => img.image,
            );
            let arr = []
            response?.data?.get_property_images.map((item) => {
              arr.push({
                url: item.image,
                props: {}
              })
            })
            setImages(temp);
            setZoomImages(arr)
            setLoader(false);
          } else {
            setImages([
              'https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60',
              'https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60',
            ]);
            setLoader(false);
          }
          let details = response.data;
          setInitialRegion({
            latitude: details?.latitude == null || details?.latitude == undefined ? 6.4406164 : parseFloat(details?.latitude),
            longitude: details?.longitude == null || details?.longitude == undefined ? 3.4785239 : parseFloat(details?.longitude),
            // latitude: 37.78825,
            // longitude: -122.4324,
            latitudeDelta: 0.0922,
            longitudeDelta: 0.0421,
          })
          setDetails(response.data);
          setHomeDetailsExtraServices(response.data.get_extra_service);
          setHomeDetailsData(response?.data?.get_amenities);

          _mapView.current.animateToRegion({
            latitude: details?.latitude == null || details?.latitude == undefined ? 6.4406164 : parseFloat(details?.latitude),
            longitude: details?.longitude == null || details?.longitude == undefined ? 3.4785239 : parseFloat(details?.longitude),
            // latitude: 37.78825,
            // longitude: -122.4324,
            latitudeDelta: 0.0922,
            longitudeDelta: 0.0421,
          });

          // console.log("response?.data?.get_amenities=============", response?.data?.get_amenities)
          // propId=""
          // setHomeData(response.data.data.properties.data)
          // console.log("data is ",data)
        }
      })
      .catch(e => {
        console.log('error', e);
        // removeValue();
      });

  };
  // console.log('-=========kkkj', homeDetailsData.length,homeDetailsExtraServices);
  const addtoFavoriteHandler = async () => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    // console.log('=======token add', Localtoken);
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
      addFavouriteApi();
    }
  };
  const addFavouriteApi = async item => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    const USER_ID = await AsyncStorage.getItem('USER_ID');

    try {
      axios({
        method: 'post',
        url: add_To_Favourite,
        data: { userId: USER_ID, product_id: propertyid },
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${Localtoken}`,
        },
      }).then(function (response) {
        // console.log('adddddddtoddd', response.data);
        if (response.data.status == true) {
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message,
          });
          onHandleHomeDetailsApi();
          // setLoader(false)
          // homePageApiNew(false)
        } else {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: response.data.message,
          });
          onHandleHomeDetailsApi();
          // setLoader(false)
          // homePageApiNew(false)
        }
      });
    } catch (error) {
      console.log('error is add to favorite home', error);
    }
  };

  const onHandlerCalenderBookingApi = () => {
    try {
      axios({
        method: 'post',
        url: propertyBookingCalender,
        data: { propertyid: propertyid, page: page },
      }).then(function (res) {
        // console.log('===========resssss', res.data.data);
        if (res.data.status == true) {
          setCalenderData(res.data.data);
        }
      });
    } catch (error) {
      console.log('error is booking date', error);
    }
  };

  // const onPressHandler = index => {
  //   // console.log('inddd', index)
  //   if (index == 0) {
  //     setCurIndex(0);
  //   } else if (index == 1) {
  //     setCurIndex(1);
  //   } else if (index == 2) {
  //     setCurIndex(2);
  //   } else if (index == 3) {
  //     setCurIndex(3);
  //   } else if (index == 4) {
  //     setCurIndex(4);
  //   } else if (index == 5) {
  //     setCurIndex(5);
  //   } else if (index == 6) {
  //     setCurIndex(6);
  //   } else if (index == 7) {
  //     setCurIndex(7);
  //   }
  // };
  // const renderItem = ({ item, index }) => {
  //   return (
  //     <TouchableOpacity
  //       key={item.id}
  //       style={{ marginVertical: 10, marginLeft: 8 }}
  //       onPress={() => onPressHandler(index)}>
  //       <Text
  //         style={[
  //           styles.title,
  //           {
  //             color: curIndex !== index ? 'black' : 'orange',
  //             textDecorationLine: curIndex !== index ? null : 'underline',
  //           },
  //         ]}>
  //         {item?.title}
  //       </Text>
  //     </TouchableOpacity>
  //   );
  // };

  const _render_item_s_offer = (list) => {
    return list.map((item, index) => {
      return (
        <View>

          <View
            style={{
              marginHorizontal: 20,
              marginTop: 10,
              flexDirection: 'row',
            }}>
            {/* <Text>{item?.get_amenity_data?.image}</Text> */}
            {
              item.get_amenity_data.image.match('svg') == 'svg' ?

                <Svg height={24} width={24} viewBox="0 0 28 25">
                  <SvgUri
                    width="100%"
                    height="100%"
                    uri={item?.get_amenity_data?.image}
                  />
                </Svg> :
                <Image source={{ uri: item?.get_amenity_data?.image }}
                  style={{ tintColor: 'orange', height: 20, width: 20, resizeMode: 'contain' }}
                />
            }
            <Text
              style={{
                fontSize: 18,
                color: color.primaryColorBlack,
                fontWeight: '600',
                marginHorizontal: 10,
              }}>
              { item?.get_amenity_data?.name?.toUpperCase()}
            </Text>
          </View>
        </View>
      )
    })
  }
  const renderImages = ({ item, index }) => {
    return (
      <FastImage
        resizeMode="contain"
        source={{ uri: item }}
        style={{ width: '100%', height: '100%' }}
      />
    );
  };

  const onShare = async () => {
    try {
      const result = await Share.share({
        title: 'Shortlet',
        // url:'https://awesome.contents.com/',
        message: `${MAIN_URL}/property-detail/${propertyid}`,
      });
      if (result.action === Share.sharedAction) {
        if (result.activityType) {
          // shared with activity type of result.activityType
        } else {
          // shared
        }
      } else if (result.action === Share.dismissedAction) {
        // dismissed
      }
    } catch (error) {
      // alert(error.message);
      console.log('errr', error);
    }
  };
  const [activeIndex, setActivityIndex] = useState(0);
  const pagination = () => {
    return (
      <Pagination
        dotsLength={details?.length}
        activeDotIndex={activeIndex}
        //   containerStyle={{ backgroundColor: 'red' }}
        dotStyle={{
          width: 16,
          height: 16,
          borderRadius: 10,
          //   marginHorizontal: 8,
          // backgroundColor: '#3C317D',
        }}
        inactiveDotStyle={{
          // Define styles for inactive dots here
          backgroundColor: '#F99428',
        }}
        inactiveDotOpacity={0.5}
        inactiveDotScale={0.6}
        inactiveDotColor="#F99428"
        dotColor="#3C317D"
      />
    );
  };
  // details.get_amenities.forEach(element => {
  //     setAmenity(element.get_amenity_data)
  // })
  // console.log('======v==v=v=vvv',details?.video_url);
  const onHandlerBooking = async () => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    if (Localtoken == null) {
      Alert.alert('Hold on!', 'Please login first?', [
        {
          text: 'Cancel',
          onPress: () => null,
          style: 'cancel',
        },
        { text: 'LOGIN', onPress: () => props?.navigation?.navigate('Logins') },
      ]);
    }else{

    
    try {
      setBookLoader(true)
      axios({
        method: "post",
        url: AddCartBooking,
        data: {
          start_date: moment(date).format('YYYY-MM-DD'),
          end_date: moment(dateLocal).format('YYYY-MM-DD'),
          total_days: dayDifference,
          per_night_price: props?.route?.params?.My_Book == 'MY_BOOK' || MY_FAV == 'MY_FAV'
            ? details?.price
            : price,
          total_booking_amount: props?.route?.params?.My_Book == 'MY_BOOK' || MY_FAV == 'MY_FAV' ? Number(details?.price)
            : Number(price)
            * Number(dayDifference) + Number(details.security_deposit_amount),
          property_id: propertyid,
          adultCount: count1,
          childCount: count2,
          infantCount: count3,
          petCount: count4,
        },
        headers: { Accept: 'application/json', Authorization: `Bearer ${Localtoken}` }
      })
        .then((res) => {
          // console.log('resssssssssssssaaddd', res.data)
          if (res.data.status == true) {
            Toast.show({
              type:"success",
              text1:res.data.message
            })
            setBookLoader(false)
            props?.navigation?.navigate('Booking', {
              price:
                props?.route?.params?.My_Book == 'MY_BOOK' || MY_FAV == 'MY_FAV' ? details?.price : price,
              convenceFee: details.security_deposit_amount,
              myId: null,
              propertyid,
              bookType: details.book_type,
              max_Gest: details.max_guest,
              points_amount: details.points_amount,
              loyalty_points: details.loyalty_points,
              hotelDetail: 'hotelDetail'
            });

          } else {
            Toast.show({
              type: "error",
              text2: res.data.message
            })
          }
          setBookLoader(false)
        })
    } catch (error) {
      console.log('eerrr', error);
      setBookLoader(false)

    }
  }
    // props?.navigation?.navigate('Booking', {
    //   // id: propertyid,
    //   price:
    //     props?.route?.params?.My_Book == 'MY_BOOK' || MY_FAV == 'MY_FAV' ? details?.price : price,
    //   convenceFee: details.security_deposit_amount,
    //   myId: null,
    //   propertyid,
    //   bookType: details.book_type,
    //   max_Gest: details.max_guest,
    //   points_amount: details.points_amount,
    //   loyalty_points: details.loyalty_points,
    //   hotelDetail: 'hotelDetail'
    // });
    // }
  };

  const onChatHandler = async () => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    // console.log('=======token add', Localtoken);
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
      navigation.navigate('Chat', {
        propertyid: propertyid,
        fromDetail: 'fromDetail',
      });
    }
  };

  let markedDay = {};

  calenderData?.monthWiseData.map(item => {
    // console.log('======day', item.split(','));
    calenderID = item.split(',');
    calenderID.map(i => {
      markedDay[i] = {
        startingDay: true,
        color: 'red',
        textColor: 'white',
        selected: true,
        // marked: true,
        selectedColor: 'red',
        endingDay: true,
        color: 'red',
        textColor: 'white',
        disableTouchEvent: true,
        disabled:true
      };
    });
  });

  let  startDay =  {}
  // details?.blockDates.map(item => {
  //   // console.log('======day', item.split(','));
  //   fromDate = item.split(',');
  //   fromDate.map(i => {
  //     startDay[i] = {
  //       startingDay: true,
  //       color: 'red',
  //       textColor: 'white',
  //       selected: true,
  //       // marked: true,
  //       selectedColor: 'red',
  //       endingDay: true,
  //       color: 'red',
  //       textColor: 'white',
  //     };
  //   });
  // });
  // const counter1 = () => {
  //   if (page < 2) {
  //     setPage(page + 1);
  //   } else {
  //     setPage(2);
  //   }
  // };

  // const counter2 = () => {
  //   if (page > -2) {
  //     setPage(page - 1);
  //   } else {
  //     setPage(-2);
  //   }
  // };


  // console.log('details title', title[1])
  return (
    <>
      {loader ? (
        <SliderShimmerHomeDetail />
      ) : (
        <View style={commonStyles.container}>
          <ScrollView showsVerticalScrollIndicator={false} style={{ backgroundColor: '#fff' }}>
            <View style={styles.container}>
              <View>
                <SliderBox
                  ImageComponent={FastImage}
                  images={images.slice(0, 10)}
                  // onSnapToItem={(index) => setActivityIndex(index) }
                  imageLoadingColor="#000000"
                  //   images={state.images}
                  dotColor="orange"
                  style={{ height: 300, width: '100%' }}
                  resizeMode="cover"
                  resizeMethod={'resize'}
                  onCurrentImagePressed={index => {
                    console.warn(`image ${index} pressed`),
                      setModalImage(index)
                    setModalVisible(true)
                  }
                  }
                  ImageLoader={false}
                  dotStyle={{
                    width: 8,
                    height: 8,
                    borderRadius: 15,
                    marginHorizontal: -10,
                    // padding: 0,
                    // margin: 0,
                    marginBottom: 10,
                  }}
                />
                {details?.length > 1 && pagination()}
              </View>
              <View
                style={{
                  flexDirection: 'row',
                  justifyContent: 'space-between',
                  top: 40,
                  position: 'absolute',
                  width: width - 30,
                  alignSelf: 'center',
                }}>
                <TouchableOpacity
                  onPress={
                    () =>
                      props?.route?.params?.My_Book == 'MY_BOOK'
                        ? props?.navigation?.navigate('MyBooking')
                        : props?.navigation?.navigate('Home')
                    // navigation.goBack()
                  }>
                  <Image
                    resizeMode="contain"
                    source={Images.Chevron_Right}
                    style={{
                      width: 25,
                      height: 25,
                    }}
                  />
                </TouchableOpacity>

                <TouchableOpacity onPress={() => addtoFavoriteHandler()}>
                  <FastImage
                    source={
                      details.is_fav === 1 ? Images.heart : Images.heartIcon
                    }
                    style={{ height: 24, width: 24 }}
                    resizeMode="contain"
                  />
                </TouchableOpacity>
              </View>
              {/* <LinearGradient
                colors={[color.appYellowColor, color.appOrangeColor]}
                style={{
                  height: 30,
                  alignSelf: 'flex-end',
                  width: 120,
                  marginRight: 20,
                  borderRadius: 15,
                  backgroundColor: color.appOrangeColor,
                  marginTop: -40,
                  justifyContent: 'center',
                  paddingHorizontal: 4,
                }}>
                <TouchableOpacity
                  onPress={() => {
                    setModalVisible(true);
                  }}>
                  <Text
                    style={{
                      // justifyContent: 'center',
                      alignSelf: 'center',
                      color: color.white,
                      fontSize: 12,
                    }}>
                    See All Photos
                  </Text>
                </TouchableOpacity>
              </LinearGradient> */}
            </View>
            <View
              style={{
                borderBottomWidth: 1,
                borderColor: color.appTextBackgoundColor,
              }}>
              <View
                style={{
                  marginHorizontal: width * (20 / 375),
                  marginTop: width * (17 / 375),
                  marginBottom: 20,
                }}>
                <View
                  style={{
                    alignItems: 'flex-start',
                    flexDirection: 'row',
                    justifyContent: 'space-between',
                  }}>
                  <Text
                    style={{
                      fontSize: 20,
                      fontWeight: '400',
                      color: color.primaryColorBlack,
                      width: width / 1.5,
                    }}>
                    {details?.title}
                  </Text>
                  <TouchableOpacity onPress={onShare}>
                    <Image
                      resizeMode="contain"
                      style={{ height: 20, width: 20, marginTop: 8 }}
                      source={Images.ShareIcons}
                    />
                  </TouchableOpacity>
                </View>

                <View
                  style={{ flexDirection: 'row', alignItems: 'center', top: 10 }}>
                  <View style={{ flexDirection: 'row' }}>
                    <Image source={Images.Broze} />
                    <Text
                      style={{
                        fontSize: 15,
                        alignSelf: 'center',
                        color: color.primaryColorBlack,
                        marginLeft: 10,
                      }}>
                      Superhost
                    </Text>
                  </View>
                  <View
                    style={{
                      borderWidth: 1,
                      height: 5,
                      width: 5,
                      borderRadius: 10,
                      backgroundColor: 'black',
                      marginHorizontal: 8,
                    }}
                  />
                  <View style={{ flexDirection: 'row', alignItems: 'center' }}>
                    <Image
                      source={Images.StartIcon}
                      style={{ height: 12, width: 12 }}
                    />
                    <Text
                      style={{
                        fontSize: 15,
                        color: color.primaryColorBlack,
                        marginHorizontal: 4,
                      }}>
                      {details?.avg_rating == null ? 0 : details?.avg_rating}
                    </Text>
                  </View>
                  <View
                    style={{
                      borderWidth: 1,
                      height: 5,
                      width: 5,
                      borderRadius: 10,
                      backgroundColor: 'black',
                      marginHorizontal: 8,
                    }}
                  />
                  <TouchableOpacity
                    onPress={() =>
                      props?.navigation?.navigate('Review', {
                        id: propertyid,
                        DATA: details,
                      })
                    }>
                    <Text
                      style={{ fontSize: 15, color: color.primaryColorBlack }}>
                      {details?.total_rating == null
                        ? 0
                        : details?.total_rating}{' '}
                      Reviews
                    </Text>
                  </TouchableOpacity>
                </View>
                <View
                  style={{
                    flexDirection: 'row',
                    backgroundColor: color.appTextBackgoundColor,
                    alignContent: 'center',
                    borderRadius: 10,
                    borderColor: color.appOrangeColor,
                    borderWidth: 1,
                    alignItems: 'center',
                    marginTop: 20,
                    height: 44,
                    width: '100%',
                  }}>
                  {/* <TouchableOpacity>
                    <Image
                      source={Images.LocationIcons}
                      style={{
                        left: 10,
                        width: 20,
                        height: 20,
                        resizeMode: 'contain',
                      }}
                    />
                  </TouchableOpacity> */}
                  <Text
                    style={{
                      left: 20,
                      fontSize: 15,
                      color: color.primaryColorBlack,
                    }}>
                    {details.type}
                  </Text>
                </View>
                <View
                  style={{
                    flexDirection: 'row',
                    marginTop: 15,
                    justifyContent: 'space-between',
                  }}>
                  <View
                    style={{
                      flexDirection: 'row',
                      justifyContent: 'center',
                      alignItems: 'center',
                    }}>
                    <Image
                      style={{
                        width: 35,
                        height: 35,
                        borderRadius: Platform.OS == 'ios' ? 20 : 20,
                      }}
                      resizeMode="cover"
                      source={{ uri: details.image }}
                    />
                    <View style={{ marginLeft: 8 }}>
                      <Text
                        style={{
                          fontSize: 14,
                          fontWeight: '500',
                          color: color.primaryColorBlack,
                          // width:100
                          width: 80,
                        }}>
                        {details.host_name}
                      </Text>
                      <Text
                        style={{ fontSize: 12, marginLeft: 2, color: '#000' }}>
                        Host
                      </Text>
                    </View>
                  </View>
                  <TouchableOpacity
                    onPress={() => onChatHandler()}
                    style={{
                      borderWidth: 1,
                      borderRadius: 6,
                      borderColor: color.appOrangeColor,
                      backgroundColor: color.appLightOrangeColor,
                      justifyContent: 'center',
                      alignItems: 'center',
                      flexDirection: 'row',
                      // marginHorizontal: 20,
                      height: 35,
                      width: '60%',
                      alignSelf: 'flex-end',
                    }}>
                    <Svg height={16} width={16} style={{ marginHorizontal: 0 }}>
                      <SvgUri
                        width="90%"
                        height="90%"
                        uri={`${MAIN_URL}/assets/web/img/shortlet/message.svg`}
                      />
                    </Svg>
                    <Text
                      style={{
                        marginLeft: 4,
                        // marginRight: 4,
                        color: color.appOrangeColor,
                        fontSize: 11,
                        // paddingHorizontal: 4,
                      }}>
                      Chat With a Booking Specialist
                    </Text>
                  </TouchableOpacity>
                </View>
                <View style={{ backgroundColor: color.white, paddingVertical: 20, marginVertical: 10, borderWidth: 1, borderColor: color.inputBoxBorderGray, borderRadius: 12 }}>
                  <View
                    style={{
                      flexDirection: 'row',
                      // marginTop: 8,
                      alignItems: 'center',
                      marginHorizontal: 20
                    }}>
                    <Text
                      style={{
                        fontSize: 24,
                        fontWeight: '500',
                        color: color.primaryColorBlack,
                      }}>
                      NGN {props?.route?.params?.My_Book == 'MY_BOOK' || MY_FAV == 'MY_FAV'
                        ? details?.price
                        : price}
                      .00
                    </Text>
                    <Text
                      style={{
                        fontSize: 15,
                        color: '#000000',
                        marginHorizontal: 8,
                      }}>
                      Night
                    </Text>
                  </View>

                  <View
                    style={{
                      height: 140,
                      borderWidth: 1,
                      marginHorizontal: 20,
                      marginTop: 10,
                      borderRadius: 4,//8,
                      borderColor: '#E1E1E1',
                    }}>
                    <View
                      style={{
                        height: 70,
                        borderWidth: 0.5,
                        borderTopWidth: 0,
                        top: 1,
                        width:Platform.OS == 'android' ? width - 88 : width - 83,
                        // borderRadius: 40,
                        borderColor: '#E1E1E1',
                        flexDirection: 'row',
                        // backgroundColor:'red'
                      }}>
                      <View
                        style={{
                          // backgroundColor: "red",
                          flex: 0.5,
                          // width:"50%",
                          paddingLeft: 16,
                          borderRightWidth: 1,
                          borderRightColor: '#f3f6f9',
                          justifyContent: 'center',
                          borderBottomLeftRadius: 40,
                          // borderBottomRightRadius:40
                          // borderBottomColor: 'red',
                          // borderTopLeftRadius:10
                        }}>
                        <Modal
                          visible={modalVisibleDate}
                          transparent
                          onBackdropPress={() => setModalVisibleDate(false)}
                        // style={{ backgroundColor: 'red', height: 300, width: '100%' }}
                        >
                          <TouchableOpacity onPress={() => setModalVisibleDate(false)} style={{ flex: 1, justifyContent: 'center', alignItems: "center", backgroundColor: '#00000080' }}>
                            <Calendar
                              // current={new Date()}
                              // current={date == 'Select Date' ? new Date() : moment(date).format('YYYY-MM-DD')}


                              minDate={new Date()}
                              // minDate={date == 'Select Date' ? new Date() : moment(date).format('YYYY-MM-DD')}

                              markedDates={markedDay
                                }

                              // maxDate={moment(date).format('YYYY-MM-DD')> moment(dateLocal).format('YYYY-MM-DD') ? new Date() : moment(dateLocal).format('YYYY-MM-DD')}
                              // maxDate={dateLocal == 'Select Date' ? "2030-09-09" : moment(dateLocal).format('YYYY-MM-DD')}
                              onDayPress={onDayPress}
                              onDayLongPress={day => {
                                console.log('selected day', day);
                              }}
                              enableSwipeMonths={true}
                              style={{ width: width - 80, borderRadius: 20 }}
                            />
                          </TouchableOpacity>

                          {/* <Calendar
                      date={date}
                      
                      onDayPress={onDayPress}
                      style={{ marginHorizontal: 20 }}
                    /> */}
                        </Modal>
                        <TouchableOpacity
                          onPress={() => setModalVisibleDate(!modalVisibleDate)}>
                          <Text
                            style={{
                              color: color.primaryColorBlack,
                              fontWeight: '600',
                            }}>
                            Check in
                          </Text>
                          {/* {
                      date == "Select Date" ?
                        <Text style={{ color: color.appTextColor }}>
                          {date}
                        </Text>
                        : */}

                          <Text style={{ color: color.appTextColor }}>
                            {moment(date).format('YYYY-MM-DD')}
                          </Text>



                          {/* } */}
                        </TouchableOpacity>
                      </View>
                      <View
                        style={{
                          backgroundColor: color.appWhiteColor,
                          borderBottomRightRadius: 10,
                          flex: 0.5,
                          paddingLeft: 16,
                          justifyContent: 'center',
                          borderBottomColor: '#cdc6c6',
                        }}>
                        <Modal
                          visible={modal}
                          transparent
                          onBackdropPress={() => setModal(false)}
                        // style={{ backgroundColor: 'red', height: 300, width: '100%' }}
                        >
                          <TouchableOpacity onPress={() => setModal(false)} style={{ flex: 1, justifyContent: 'center', alignItems: "center", backgroundColor: '#00000080' }}>

                            <Calendar
                              // date={dateLocal}
                              minDate={moment(date).format('YYYY-MM-DD')}
                              // minDate={date == 'Select Date' ? new Date() : moment(date).format('YYYY-MM-DD')}
                              // Maximum date that can be selected, dates after maxDate will be grayed out. Default = undefined
                              // maxDate={'3034-05-30'}
                              markedDates={markedDay
                              }
                              onDayPress={onDayPressLocal}
                              style={{ marginHorizontal: 20, width: width - 80, borderRadius: 20 }}
                            />
                          </TouchableOpacity>
                        </Modal>
                        <TouchableOpacity
                          onPress={() => {
                            day => setDateLocal(day), setModal(!modal);
                          }}>
                          <Text
                            style={{
                              color: color.primaryColorBlack,
                              fontWeight: '600',
                            }}>
                            Check out
                          </Text>
                          {/* {
                      dateLocal == "Select Date" ?
                        <Text style={{ color: color.appTextColor }}>
                          {dateLocal}
                        </Text>
                        : */}

                          <Text style={{ color: color.appTextColor }}>
                            {moment(dateLocal).format('YYYY-MM-DD')}
                          </Text>


                          {/* Platform.OS == 'android' ? <Text style={{ color: color.appTextColor }}>
                          {moment(dateLocal).format('YYYY-MM-DD')}
                        </Text> : <Text style={{ color: color.appTextColor }}>
                          {dateLocalTrue ? moment(dateLocal).format('YYYY-MM-DD') : moment(dateLocal, "DD/MM/YYYY, h:mm:ss A").format("YYYY-MM-DD")}
                        </Text> */}


                          {/* } */}

                          {/* <Text style={{ color: color.appTextColor }}>
                      {Platform.OS == 'ios' ? dateLocal : moment(dateLocal).format('YYYY-MM-DD')}
                    </Text> */}
                        </TouchableOpacity>
                      </View>
                    </View>
                    <TouchableOpacity onPress={() => setGuestModal(!guestModal)}
                      style={{ paddingLeft: 16 }}>
                      <Text
                        style={{
                          color: color.primaryColorBlack,
                          marginTop: 8,
                          fontWeight: '600',
                        }}>
                        Who
                      </Text>

                      {/* <Dropdown
                                style={styles.dropdown}
                                data={data}
                                search={false}
                                maxHeight={300}
                                labelField="label"
                                valueField="value"
                                // placeholder=""
                                // searchPlaceholder="5 guests"
                                value={value}
                                onChange={item => {
                                    setValue(item.value);
                                }} /> */}
                      <TouchableOpacity

                        onPress={() => setGuestModal(!guestModal)}
                        style={{
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                        }}>
                        <Text style={{ color: '#000' }}>{count1 + count2 + count3 + count4} guest</Text>
                        <TouchableOpacity
                          onPress={() => setGuestModal(!guestModal)}
                          style={{
                            transform: [{ rotate: '95deg' }],
                            marginHorizontal: 20,
                          }}>
                          <Image
                            source={Images.ArrowIcons}
                            style={{ tintColor: '#707070' }}
                          />
                        </TouchableOpacity>
                      </TouchableOpacity>

                    </TouchableOpacity>
                  </View>
                  {guestModal == true ? (
                    <View
                      style={{
                        borderRadius: 10,
                        marginHorizontal: 20,
                        // alignSelf: 'center',
                        marginTop: 0, backgroundColor: color.appTextBackgoundColor, zIndex: 99999
                      }}>
                      <View
                        style={{
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                          marginHorizontal: 0,
                          backgroundColor: color.appTextBackgoundColor,
                        }}>
                        <View style={{ marginLeft: 10, marginTop: 10, width: 200 }}>
                          <Text
                            style={{
                              fontSize: 15,
                              fontWeight: '600',
                              color: color.primaryColorBlack,
                            }}>
                            Adults
                          </Text>
                          <Text style={{ fontSize: 15, fontWeight: '400', color: '#000' }}>
                            Ages 13 or above
                          </Text>
                        </View>
                        <View
                          style={{
                            flexDirection: 'row',
                            marginTop: 10,
                            marginRight: 10,
                            alignItems: 'center',
                          }}>
                          <TouchableOpacity
                            activeOpacity={0.9}
                            onPress={counter1}
                            style={styles.AddingBackground}>
                            <Image
                              source={Images.minusIcon}
                              style={{ height: 20, width: 20 }}
                            />
                          </TouchableOpacity>
                          <View
                            style={{
                              height: 30,
                              width: 30,
                              alignItems: 'center',
                              justifyContent: 'center',
                            }}>
                            <Text
                              style={{
                                color: color.primaryColorBlack,
                                fontWeight: '500',
                                alignSelf: 'center',
                              }}>
                              {count1}
                            </Text>
                          </View>
                          <TouchableOpacity
                            activeOpacity={0.9}
                            onPress={() => setCount1(count1 + 1)}
                            style={styles.AddingBackground}>
                            {/* <Text style={{ color: color.primaryColorBlack }}>+</Text> */}
                            <Image
                              source={Images.plusIcon}
                              style={{ height: 20, width: 20 }}
                            />
                          </TouchableOpacity>
                        </View>
                      </View>
                      <View
                        style={{
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                          marginHorizontal: 0,
                          backgroundColor: color.appTextBackgoundColor,
                        }}>
                        <View style={{ marginLeft: 10, marginTop: 10, width: 200 }}>
                          <Text
                            style={{
                              fontSize: 15,
                              fontWeight: '600',
                              color: color.primaryColorBlack,
                            }}>
                            Children
                          </Text>
                          <Text style={{ fontSize: 15, fontWeight: '400', color: '#000' }}>
                            Ages 2 - 12
                          </Text>
                        </View>
                        <View
                          style={{
                            flexDirection: 'row',
                            marginTop: 10,
                            marginRight: 10,
                            alignItems: 'center',
                          }}>
                          <TouchableOpacity
                            activeOpacity={0.9}
                            onPress={counter2}
                            style={styles.AddingBackground}>
                            {/* <Text style={{ color: color.primaryColorBlack, fontSize: 20 }}>-</Text> */}
                            <Image
                              source={Images.minusIcon}
                              style={{ height: 20, width: 20 }}
                            />
                          </TouchableOpacity>
                          <View
                            style={{
                              height: 30,
                              width: 30,
                              alignItems: 'center',
                              justifyContent: 'center',
                            }}>
                            <Text
                              style={{
                                color: color.primaryColorBlack,
                                fontWeight: '500',
                              }}>
                              {count2}
                            </Text>
                          </View>
                          <TouchableOpacity
                            activeOpacity={0.9}
                            onPress={() => setCount2(count2 + 1)}
                            style={styles.AddingBackground}>
                            {/* <Text style={{ color: color.primaryColorBlack }}>+</Text> */}
                            <Image
                              source={Images.plusIcon}
                              style={{ height: 20, width: 20 }}
                            />
                          </TouchableOpacity>
                        </View>
                      </View>
                      <View
                        style={{
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                          marginHorizontal: 0,
                          backgroundColor: color.appTextBackgoundColor,
                        }}>
                        <View style={{ marginLeft: 10, marginTop: 10, width: 200 }}>
                          <Text
                            style={{
                              fontSize: 15,
                              fontWeight: '600',
                              color: color.primaryColorBlack,
                            }}>
                            Infants
                          </Text>
                          <Text style={{ fontSize: 15, fontWeight: '400', color: '#000' }}>
                            Under 2
                          </Text>
                        </View>
                        <View
                          style={{
                            flexDirection: 'row',
                            marginTop: 10,
                            marginRight: 10,
                            alignItems: 'center',
                          }}>
                          <TouchableOpacity
                            activeOpacity={0.9}
                            onPress={counter3}
                            style={styles.AddingBackground}>
                            {/* <Text style={{ color: color.primaryColorBlack, fontSize: 20 }}>-</Text> */}
                            <Image
                              source={Images.minusIcon}
                              style={{ height: 20, width: 20 }}
                            />
                          </TouchableOpacity>
                          <View
                            style={{
                              height: 30,
                              width: 30,
                              alignItems: 'center',
                              justifyContent: 'center',
                            }}>
                            <Text
                              style={{
                                color: color.primaryColorBlack,
                                fontWeight: '500',
                              }}>
                              {count3}
                            </Text>
                          </View>
                          <TouchableOpacity
                            activeOpacity={0.9}
                            onPress={() => setCount3(count3 + 1)}
                            style={styles.AddingBackground}>
                            {/* <Text style={{ color: color.primaryColorBlack }}>+</Text> */}
                            <Image
                              source={Images.plusIcon}
                              style={{ height: 20, width: 20 }}
                            />
                          </TouchableOpacity>
                        </View>
                      </View>
                      <View
                        style={{
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                          marginHorizontal: 0,
                          backgroundColor: color.appTextBackgoundColor,
                        }}>
                        <View style={{ marginLeft: 10, marginTop: 10, width: 200 }}>
                          <Text
                            style={{
                              fontSize: 15,
                              fontWeight: '600',
                              color: color.primaryColorBlack,
                            }}>
                            Pets
                          </Text>
                          <Text style={{ fontSize: 15, fontWeight: '400', color: '#000' }}>
                            Bringing a service animal?
                          </Text>
                        </View>
                        <View
                          style={{
                            flexDirection: 'row',
                            marginTop: 10,
                            marginRight: 10,
                            marginBottom: 20,
                            alignItems: 'center',
                          }}>
                          <TouchableOpacity
                            activeOpacity={0.5}
                            onPress={counter4}
                            style={styles.AddingBackground}>
                            {/* <Text style={{ color: color.primaryColorBlack, fontSize: 20 }}>-</Text> */}
                            <Image
                              source={Images.minusIcon}
                              style={{ height: 20, width: 20 }}
                            />
                          </TouchableOpacity>
                          <View
                            style={{
                              height: 30,
                              width: 30,
                              alignItems: 'center',
                              justifyContent: 'center',
                            }}>
                            <Text
                              style={{
                                color: color.primaryColorBlack,
                                fontWeight: '500',
                              }}>
                              {count4}
                            </Text>
                          </View>
                          <TouchableOpacity
                            activeOpacity={0.9}
                            onPress={() => setCount4(count4 + 1)}
                            style={styles.AddingBackground}>
                            {/* <Text style={{ color: color.primaryColorBlack }}>+</Text> */}
                            <Image
                              source={Images.plusIcon}
                              style={{ height: 20, width: 20 }}
                            />
                          </TouchableOpacity>
                        </View>
                      </View>
                    </View>
                  ) : null}
                  <TouchableOpacity
                    activeOpacity={0.9}
                    // onPress={() =>[
                    //   navigation.navigate('Booking', { id: propertyid,price:price,convenceFee:details.security_deposit_amount,myId:null },console.log("convenceFee===",details.security_deposit_amount))
                    // ]}

                    onPress={() =>
                      count1 + count2 + count3 + count4 > 0 ? onHandlerBooking() : Toast.show({ type: "error", text1: "Please select atleaset one guest" })
                    }
                    style={{
                      marginTop: 15,
                      backgroundColor: color.appOrangeColor,
                      borderRadius: 10,
                      padding: width * (10 / 375),
                      flexDirection: 'row',
                      paddingStart: width * (10 / 375),
                      // marginBottom: 10,
                      alignItems: 'center',
                      marginHorizontal: 20
                    }}>
                    <View style={{ flex: 0.5 }}>
                      <Image
                        source={Images.whiteDot}
                        style={{
                          width: 25,
                          height: 25,
                          resizeMode: 'cover',
                        }}
                      />
                    </View>
                    <View style={{flexDirection:'row',justifyContent:"center",alignItems:"center"}}>

                    <Text
                      style={{
                        fontSize: width * (20 / 375),
                        color: color.appWhiteColor,
                      }}>
                      {details.book_type === 'Reserve' ? 'Reserve' : 'Book Now'}
                    </Text>
                   
                    <View style={{marginLeft:20}}>
                   {bookLoader ?   <ActivityIndicator size={'small'} color={'#fff'}/> : null }
                    </View>
                        </View>
                  </TouchableOpacity>
                  <Text style={{ marginHorizontal: 20, textAlign: "justify", marginVertical: 10, color: color.appTextColor }}>This accommodation has been set to   {details.book_type === 'Reserve' ? 'Reserve' : 'Book Now'} by host because it is available Booking well be subject to cancellation policy if cancelled.</Text>
                  <View
                    style={{
                      justifyContent: 'space-between',
                      flexDirection: 'row',
                      marginTop: 5,
                      marginHorizontal: 20
                    }}>
                    <Text
                      style={{
                        fontWeight: '500',
                        fontSize: 14,
                        color: color.appTextColor,
                      }}>
                      NGN {props?.route?.params?.My_Book == 'MY_BOOK' || MY_FAV == 'MY_FAV'
                        ? details?.price
                        : price}
                      .00 X {dayDifference} nights
                      {/* NGN {price}.00 x {dayDifference} nights */}
                    </Text>
                    <Text
                      style={{
                        fontSize: 13,
                        fontWeight: '500',
                        color: color.primaryColorBlack,
                      }}>
                      NGN {props?.route?.params?.My_Book == 'MY_BOOK' || MY_FAV == 'MY_FAV'
                        ? details?.price
                        : price
                        * dayDifference}
                      {/* NGN {price * dayDifference} */}
                    </Text>
                  </View>
                  <View
                    style={{
                      justifyContent: 'space-between',
                      flexDirection: 'row',
                      marginTop: 5,
                      marginHorizontal: 20
                    }}>
                    <Text
                      style={{
                        fontWeight: '500',
                        fontSize: 16,
                        color: color.appTextColor,
                      }}>
                      Caution Fee
                    </Text>
                    <Text
                      style={{
                        fontWeight: '500',
                        fontSize: 16,
                        color: color.primaryColorBlack,
                      }}>
                      {/* NGN {convenceFee == null
                  ? 0
                  : convenceFee} */}
                      NGN {details.security_deposit_amount == null ? '0' :details.security_deposit_amount}
                    </Text>
                  </View>
                  <View
                    style={{
                      borderBottomWidth: 1,
                      opacity: 0.5,
                      borderColor: 'gray',
                      marginHorizontal: 20,
                      marginVertical: 10
                    }}
                  />
                  <View
                    style={{
                      justifyContent: 'space-between',
                      flexDirection: 'row',
                      marginTop: 5,
                      marginHorizontal: 20
                    }}>
                    <Text
                      style={{
                        fontWeight: '500',
                        fontSize: 16,
                        color: color.appTextColor,
                      }}>
                      Total Price
                    </Text>
                    <Text
                      style={{
                        fontWeight: '500',
                        fontSize: 16,
                        color: color.primaryColorBlack,
                      }}>
                      {/* NGN {convenceFee == null
                  ? 0
                  : convenceFee} */}
                      NGN {props?.route?.params?.My_Book == 'MY_BOOK' || MY_FAV == 'MY_FAV'
                        ? Number(details?.price)
                        : Number(price)
                        * Number(dayDifference) + Number(details.security_deposit_amount)}
                    </Text>
                  </View>
                </View>
              </View>
              <View
                style={{
                  borderBottomWidth: 1,
                  opacity: 0.5,
                  borderColor: 'gray',
                }}
              />
            </View>
            {/* <FlatList
              data={Data}
              horizontal
              renderItem={renderItem}
              keyExtractor={(item, index) => index.toString()}
              showsHorizontalScrollIndicator={false}
            /> */}
            <View style={{ marginHorizontal: 0, marginTop: 10 }}>
              {/* {curIndex == 0 && ( */}
              <View style={{ flexDirection: 'row', backgroundColor: '#f8f9fa', justifyContent: 'flex-start', alignItems: 'center', marginHorizontal: 20, borderRadius: 8, padding: 12 }}>
                <View style={{ flexDirection: 'row', }}>
                  <View
                    style={{
                      flexDirection: 'row',
                      marginTop: 4,
                      height: 40,
                      padding: 6,
                      borderRadius: width * (30 / 375),
                      borderWidth: 1,
                      borderColor: color.appTextColor,
                      justifyContent: 'center',
                      alignItems: 'center',
                      marginHorizontal: 10,
                      paddingHorizontal: 10,
                      backgroundColor: '#fff'
                    }}>
                    <View>
                      <Image
                        source={Images.GroupIcons}
                        style={{
                          marginRight: 6,
                          width: 20,
                          height: 20,
                          resizeMode: 'contain',
                        }}
                      />
                    </View>
                    <View>
                      <Text style={{ marginHorizontal: 2, color: '#000' }}>
                        {/* Max Guest : */}
                        {details.max_guest}
                      </Text>
                    </View>
                  </View>
                  <View
                    style={{
                      flexDirection: 'row',
                      marginTop: 4,
                      borderRadius: width * (30 / 375),
                      borderWidth: 1,
                      borderColor: color.appTextColor,
                      justifyContent: 'center',
                      alignItems: 'center',
                      paddingHorizontal: 10,
                      backgroundColor: '#fff'
                    }}>
                    <View>
                      <Image
                        source={Images.BedIcons}
                        style={{
                          marginRight: 6,
                          width: 20,
                          height: 20,
                          resizeMode: 'contain',
                        }}
                      />
                    </View>
                    <View>
                      <Text style={{ marginHorizontal: 2, color: '#000' }}>
                        {/* King Size Beds: */}
                        {details != ''
                          ? details?.get_property_bedroom[0]
                            ?.no_of_kingsize_bed
                          : 0}
                      </Text>
                    </View>
                  </View>
                </View>
                <View style={{ flexDirection: 'row', marginTop: 0 }}>
                  <View
                    style={{
                      flexDirection: 'row',
                      marginTop: 4,
                      height: 40,
                      borderRadius: width * (30 / 375),
                      borderWidth: 1,
                      borderColor: color.appTextColor,
                      justifyContent: 'center',
                      alignItems: 'center',
                      marginHorizontal: 10,
                      paddingHorizontal: 10,
                      backgroundColor: '#fff'
                    }}>
                    <View>
                      <Image
                        source={Images.BedIcons}
                        style={{
                          marginRight: 6,
                          width: 20,
                          height: 20,
                          resizeMode: 'contain',
                        }}
                      />
                    </View>
                    <View>
                      <Text style={{ marginHorizontal: 2, color: '#000' }}>
                        {/* Qween Size Beds: */}
                        {details != ''
                          ? details?.get_property_bedroom[0]
                            ?.no_of_qweensize_bed
                          : 0}
                      </Text>
                    </View>
                  </View>
                  <View
                    style={{
                      flexDirection: 'row',
                      marginTop: 4,
                      borderRadius: width * (30 / 375),
                      borderWidth: 1,
                      borderColor: color.appTextColor,
                      justifyContent: 'center',
                      alignItems: 'center',
                      paddingHorizontal: 10,
                      backgroundColor: '#fff'
                    }}>
                    <View>
                      <Image
                        source={Images.homeIcon}
                        style={{
                          marginRight: 6,
                          width: 20,
                          height: 20,
                          resizeMode: 'contain',
                          tintColor: 'black',
                        }}
                      />
                    </View>
                    <View>
                      <Text style={{ marginHorizontal: 2, color: '#000' }}>
                        {/* Bedrooms: */}
                        {details != ''
                          ? details?.get_property_bedroom[0]?.no_of_bedrooms
                          : 0}
                      </Text>
                    </View>
                  </View>
                </View>
              </View>
              {/* )} */}
            </View>
            <View style={{ marginTop: 10 }}>
              {/* {curIndex == 1 && ( */}
              <Text style={{ marginHorizontal: 20, fontSize: 18, color: 'black', fontWeight: '600' }}>Description</Text>
              <View style={{ marginHorizontal: 20 }}>
                {/* <Text
                    style={{
                      width: '100%',
                      color: color.primaryColorBlack,
                      fontSize: 16,
                      fontWeight: '600',
                    }}>
                    {details?.description}
                  </Text> */}

                <RenderHTML
                  contentWidth={width}
                  source={source}
                  defaultTextProps={{ style: { color: '#000', textAlign: 'justify' } }}
                />
                <View
                  style={{
                    borderBottomWidth: 1,
                    // marginHorizontal: 20,
                    borderBottomColor: '#f3f6f9',
                    marginVertical: 10,
                  }}
                />
              </View>
              {/* )} */}
            </View>
            <View>
              {/* {curIndex == 2 && */}
              <View style={{ marginTop: 10 }}>
                <Text style={{ marginHorizontal: 20, fontSize: 18, color: 'black', fontWeight: '600' }}>Special Features</Text>
                <ScrollView>{_render_item_s_offer(homeDetailsData || [])}</ScrollView>

                <View
                  style={{
                    borderWidth: 0.5,
                    borderColor: '#f3f6f9',
                    marginVertical: 5,
                  }}
                />
                <View>
                  {moreFeature ? null : (
                    <TouchableOpacity
                      style={{ marginHorizontal: 20, marginTop: 10 }}
                      onPress={() => setMoreFeature(true)}>
                      <Text style={{ color: '#F1592A' }}>
                        Show More Features
                      </Text>
                    </TouchableOpacity>
                  )}
                  {moreFeature ? (
                    <View style={{ marginHorizontal: 20 }}>
                      <Text
                        style={{ marginTop: 5, color: '#000', fontSize: 16 }}>
                        Bedroom(s)
                      </Text>
                      <Text style={{ color: '#000' }}>
                        {details?.get_property_bedroom[0]?.no_of_bedrooms}{' '}
                        Bedrooms
                      </Text>
                      <Text style={{ color: '#000' }}>
                        {details?.get_property_bedroom[0]?.no_of_kingsize_bed}{' '}
                        King Size Beds
                      </Text>
                      <Text style={{ color: '#000' }}>
                        {
                          details?.get_property_bedroom[0]
                            ?.no_of_qweensize_bed
                        }{' '}
                        Queen Size Beds
                      </Text>
                      <View
                        style={{
                          borderWidth: 0.5,
                          borderColor: '#f3f6f9',
                          marginVertical: 5,
                        }}
                      />
                      <View>
                        <Text style={{ color: '#000', fontSize: 16 }}>
                          Kitchen
                        </Text>
                        <Text style={{ color: '#000' }}>
                          {details?.get_property_kitchen[0]?.no_of_kitchens}{' '}
                          Kitchen
                        </Text>
                        <View
                          style={{
                            borderWidth: 0.5,
                            borderColor: '#f3f6f9',
                            marginVertical: 5,
                          }}
                        />
                        <View>
                          <Text style={{ color: '#000', fontSize: 16 }}>
                            Bathroom(s)
                          </Text>
                          <Text style={{ color: '#000' }}>
                            {
                              details?.get_pro_property_bathroom[0]
                                ?.bathroom_with_bathtub
                            }{' '}
                            Bathroom With Bathtub
                          </Text>
                          <Text style={{ color: '#000' }}>
                            {
                              details?.get_pro_property_bathroom[0]
                                ?.bathroom_with_shower
                            }{' '}
                            Bathrooms With Shower
                          </Text>
                          <Text style={{ color: '#000' }}>
                            {details?.get_pro_property_bathroom[0]?.toilets}{' '}
                            Toilets
                          </Text>
                          <View
                            style={{
                              borderWidth: 0.5,
                              borderColor: '#f3f6f9',
                              marginVertical: 5,
                            }}
                          />
                        </View>
                        <View>
                          <Text style={{ fontSize: 16, color: '#000' }}>
                            General
                          </Text>
                          <Text style={{ color: '#000' }}>
                            {
                              details?.get_property_bedding[0]
                                ?.washing_machine
                            }{' '}
                            Washing Machine
                          </Text>
                          <Text style={{ color: '#000' }}>
                            {
                              details?.get_property_bedding[0]
                                ?.no_of_television
                            }{' '}
                            TVs
                          </Text>
                          <Text style={{ color: '#000' }}>
                            {details?.get_property_bedding[0]?.satellite_tv}
                            TV Satellite
                          </Text>
                          <Text style={{ color: '#000' }}>
                            {details?.get_property_bedding[0]?.radio} Radio
                          </Text>
                          <Text style={{ color: '#000' }}>
                            {details?.get_property_bedding[0]?.dryer} Dryer
                          </Text>
                          <Text style={{ color: '#000' }}>
                            {details?.get_property_bedding[0]?.dvd_player} Dvd
                            Player
                          </Text>
                        </View>
                      </View>
                    </View>
                  ) : null}
                  {moreFeature ? (
                    <TouchableOpacity
                      style={{ marginHorizontal: 20, marginTop: 10 }}
                      onPress={() => setMoreFeature(false)}>
                      <Text style={{ color: '#F1592A' }}>
                        Less More Features
                      </Text>
                    </TouchableOpacity>
                  ) : null}
                </View>
              </View>

              {/* } */}
            </View>

            <View style={{ marginTop: 8 }}>
              <Text style={{ marginHorizontal: 20, fontSize: 18, color: 'black', fontWeight: '600' }}>Optional Services</Text>

              {
                // curIndex == 3 &&
                (homeDetailsExtraServices.length == 0 ?
                  <View style={{ padding: 12 }}>
                    <Text style={{ color: 'black', marginLeft: 8 }}>No data found</Text>
                  </View>
                  : (
                    homeDetailsExtraServices?.map(item => {
                      // console.log('===',item.length)
                      return (
                        <View
                          style={{ flexDirection: 'row', marginHorizontal: 20 }}>
                          <Text
                            style={{
                              fontSize: 20,
                              color: 'black',
                              fontWeight: '400',
                            }}>
                            {homeDetailsExtraServices.length > 0
                              ? item?.get_service_data?.name
                              : null}
                            <Text
                              style={{ color: '#707070', marginHorizontal: 10 }}>
                              Available
                            </Text>
                          </Text>
                        </View>
                      );
                    })
                  ))}
            </View>
            <View>
              <Text style={{ marginHorizontal: 20, fontSize: 18, color: 'black', fontWeight: '600', marginBottom: 5 }}>Your Schedule</Text>

              {/* {curIndex == 4 && ( */}
              <View>
                <View
                  style={{
                    // flexDirection: 'row',
                    // justifyContent: 'space-between',
                    // alignItems: 'center',
                    backgroundColor: '#fff',
                    borderRadius: 16,
                    borderWidth: 1,
                    padding: 8,
                    marginHorizontal: 14,
                    borderColor: '#f3f6f9',
                    marginBottom: 8
                  }}>
                  <View style={{ marginHorizontal: 0 }}>
                    <Text style={{ color: '#000' }}>Check In</Text>
                    <Text style={{ color: '#000' }}>
                      From {details?.check_in_from_time} to{' '}
                      {details?.check_in_to_time} Every day
                    </Text>
                  </View>

                </View>
                <View
                  style={{
                    // flexDirection: 'row',
                    // justifyContent: 'space-between',
                    // alignItems: 'center',
                    backgroundColor: '#fff',
                    borderRadius: 16,
                    borderWidth: 1,
                    padding: 8,
                    marginHorizontal: 14,
                    borderColor: '#f3f6f9',
                  }}>
                  <View style={{ marginHorizontal: 0 }}>
                    <Text style={{ color: '#000' }}>Check Out</Text>
                    <Text style={{ color: '#000' }}>
                      Before {details?.check_out_time}
                    </Text>
                  </View>
                </View>
                <View
                  style={{
                    borderBottomWidth: 1,
                    marginHorizontal: 20,
                    borderBottomColor: '#f3f6f9',
                    marginVertical: 10,
                  }}
                />
                {/* <View style={{marginHorizontal: 20, marginVertical: 5}}>
                    <Text
                      style={{fontSize: 16, color: '#000', fontWeight: '400'}}>
                      1 nights in{' '}
                      {details?.get_property_address[0]?.get_property_country
                        ?.name == null
                        ? null
                        : details?.get_property_address[0]?.get_property_country
                            ?.name}
                      ,
                      {details?.get_property_address[0]?.get_property_province
                        ?.name == null
                        ? null
                        : details?.get_property_address[0]
                            ?.get_property_province?.name}
                      ,
                      {details?.get_property_address[0]?.get_property_area
                        ?.name == null
                        ? null
                        : details?.get_property_address[0]?.get_property_area
                            ?.name}
                      ,
                      {details?.get_property_address[0]?.get_property_city
                        ?.name == null
                        ? null
                        : details?.get_property_address[0]?.get_property_city
                            ?.name}{' '}
                    </Text>
                  </View> */}
                <View style={{ backgroundColor: '#fff', marginHorizontal: 0 }}>
                  <Calendar
                    minDate={calenderData?.start_date}
                    maxDate={calenderData?.end_date}
                    markedDates={markedDay}
                    style={{
                      marginTop: 20,
                      backgroundColor: '#f3f6f9',
                      marginHorizontal: 20,
                    }}
                  />
                  {/* <View
                      style={{
                        flexDirection: 'row',
                        marginTop: 40,
                        justifyContent: 'space-between',
                        alignItems: 'center',
                        marginHorizontal: 20,
                      }}>
                      <TouchableOpacity onPress={counter2}>
                        <Image
                          source={Images.arrIcon}
                          style={{ transform: [{ rotate: '188deg' }],height:24,width:24,resizeMode:'contain' }}
                        />
                      </TouchableOpacity>
                      <TouchableOpacity onPress={counter1}>
                        <Image
                          source={Images.arrIcon}
                          style={{ transform: [{ rotate: '8deg' }],height:24,width:24 ,resizeMode:'contain'}}
                        />
                      </TouchableOpacity>
                    </View> */}
                </View>
              </View>
              {/* )} */}
            </View>
            <View style={{ marginTop: 20 }}>
              {/* {curIndex == 5 && ( */}
              <Text style={{ marginHorizontal: 20, fontSize: 18, color: 'black', fontWeight: '600', marginBottom: 5 }}>Watch The Video</Text>

              <View>
                {/* {
                    details?.video_url == null ?
                    <View style={{borderWidth:0,height:200,width:'90%',justifyContent:'center',alignItems:'center',marginHorizontal:20,borderRadius:4,backgroundColor:color.inputBoxBorderGray}}>
                    <Text style={{alignSelf:'center',color:color.primaryColorBlack}}>No Video Found</Text>
                    </View> : */}
                {
                  details?.video_url == null ?
                    <View style={{ borderWidth: 0, height: 200, width: '90%', justifyContent: 'center', alignItems: 'center', marginHorizontal: 20, borderRadius: 4, backgroundColor: color.inputBoxBorderGray }}>
                      <Text style={{ alignSelf: 'center', color: color.primaryColorBlack }}>No Video Found</Text>
                    </View> :
                    <View style={{ marginTop: 10, marginHorizontal: 20 }}>
                      <YoutubePlayer
                        mute={true}
                        height={height / 3.7}
                        width={width - 40}

                        play={false}
                        forceAndroidAutoplay={true}
                        videoId={details.video_url == null ? arr : arr[1]}
                        initialPlayerParams={{
                          controls: false,
                          loop: true,
                          rel: false,
                          iv_load_policy: 3,
                          modestbranding: 1,
                        }}
                      // playList={[youtubeUrl]}
                      />
                      {/* <YouTube
                    apiKey="AIzaSyCMCFZuq6tfiVPHTWDShyhSovLIlS_EkOg"
                    // apiKey="AIzaSyBVQa9IUYZbFpE_fcfyH2dETER5-UNxtm4"
                    videoId="xCHr095tjeM" //{details?.video_url} // The YouTube video ID
                    // videoId={details.video_url == null ? arr : arr[1]} //{details?.video_url} // The YouTube video ID
                    play={true} // control playback of video with true/false
                    // fullscreen // control whether the video should play in fullscreen or inline
                    loop={false} // control whether the video should loop when ended
                    style={{ alignSelf: 'stretch', height: 300 }}
                    controls={1}
                    onError={e => {console.log(e)}}
                   
                  /> */}

                    </View>
                }


              </View>
              {/* )} */}
            </View>
            <View style={{ marginTop: 10 }}>
              {/* {curIndex == 6 && ( */}
              <Text style={{ marginHorizontal: 20, fontSize: 18, color: 'black', fontWeight: '600', marginBottom: 5 }}>THINGS TO KNOW</Text>

              <View>
                <View style={{ marginHorizontal: 20 }}>
                  <Text
                    style={{ fontSize: 16, color: '#000', fontWeight: '400' }}>
                    Booking Condition
                  </Text>
                  <Text style={{ fontSize: 14, color: '#000' }}>
                    {details?.booking_condition}
                  </Text>
                </View>
                <View
                  style={{
                    borderWidth: 1,
                    marginTop: 8,
                    borderColor: '#f3f6f9',
                    marginHorizontal: 20,
                  }}
                />
                <View style={{ marginHorizontal: 20, marginVertical: 10 }}>
                  <Text
                    style={{ fontSize: 16, color: '#000', fontWeight: '400' }}>
                    Cancellation Policy
                  </Text>
                  <Text style={{ fontSize: 14, color: '#000' }}>
                    {details?.cancellation_policy == null
                      ? 'Shortlet'
                      : details?.cancellation_policy}
                  </Text>
                </View>
                <View
                  style={{
                    borderWidth: 1,
                    marginVertical: 4,
                    borderColor: '#f3f6f9',
                    marginHorizontal: 20,
                  }}
                />
                <View style={{ marginHorizontal: 20, marginTop: 0 }}>
                  <Text
                    style={{ fontSize: 16, color: '#000', fontWeight: '400' }}>
                    Caution Fee
                  </Text>
                  <Text style={{ fontSize: 14, color: '#000' }}>
                    Amount:
                    <Text style={{ color: 'gray' }}>
                      {' '}
                      NGN
                      {details?.security_deposit_amount == null
                        ? 0
                        : details?.security_deposit_amount}{' '}
                      /booking
                    </Text>
                  </Text>
                  <Text style={{ fontSize: 14, color: '#000' }}>
                    Payment method:
                    <Text style={{ color: 'gray' }}>
                      {' '}
                      Both options are available (Card or Bank Transfer)
                    </Text>
                  </Text>
                  <Text style={{ fontSize: 14, color: 'gray' }}>
                    Refund of caution fee to be paid 24 hrs after checkout.
                  </Text>
                </View>
              </View>
              {/* )} */}
            </View>
            <View style={{ marginTop: 10 }}>
              {/* {curIndex == 7 && ( */}
              <Text style={{ marginHorizontal: 20, fontSize: 18, color: 'black', fontWeight: '600', marginBottom: 5 }}>Map and Distances</Text>

              <View style={{ marginTop: 10 }}>
                <MapView
                  ref={_mapView}
                  paddingAdjustmentBehavior={'automatic'}
                  showsIndoors={true}
                  showsIndoorLevelPicker={false}
                  showsTraffic={false}
                  toolbarEnabled={false}
                  loadingEnabled={true}
                  showsMyLocationButton={true}
                  showsUserLocation={true}
                  showsBuildings={true}
                  showsCompass
                  style={{
                    flex: 1,
                    marginTop: 5,
                    // marginHorizontal: 20,
                    height: height / 2,//width * (217 / 375),
                    borderWidth: 1,
                    borderRadius: 20,
                  }}
                  initialRegion={initialRegion}
                  key={propertyid}

                // onRegionChangeComplete={(e) => setInitialRegion(e)}
                >
                  {initialRegion.latitude != undefined && initialRegion.latitude != null && initialRegion.latitude != "" &&
                    <Marker
                      key={propertyid}
                      coordinate={initialRegion}
                      
                    />
                  }
                </MapView>
                {/* <Text>{JSON.stringify(initialRegion)}</Text> */}
              </View>
              {/* )} */}
            </View>
            <View style={{ marginBottom: 100 }} />
            <Modal
              animationType="slide"
              transparent={true}
              visible={modalVisible}
              onRequestClose={() => {
                // Alert.alert('Modal has been closed.');
                setModalVisible(!modalVisible);
              }}>


              <View
                style={{
                  flex: 1,
                  justifyContent: 'center',
                  alignItems: 'center',
                  // marginTop: 22,
                  backgroundColor: 'rgba(0, 0, 0,0.6)',
                  // transform:[{rotate:'90deg'}]
                }}>
                <View
                  style={{
                    flex: 1,
                    width: '100%',
                    height: '100%',
                    // justifyContent: 'center',
                    backgroundColor: '#fff',
                    borderRadius: 16,
                    // marginHorizontal: '10%',
                  }}>
                  <TouchableOpacity
                    style={{ marginRight: '-5%' }}
                    onPress={() => setModalVisible(false)}>
                    <Image
                      source={Images.cancelImageIcon}
                      style={{
                        // paddingLeft: 70,
                        width: 32,
                        height: 32,
                        // borderRadius: 8,
                        // borderWidth: 1,
                        // borderColor: 'red',
                        marginRight: 30,
                        alignSelf: 'flex-end',

                        marginTop: Platform.OS == 'ios' ? 50 : 4,
                      }}
                      resizeMode="contain"
                    />
                  </TouchableOpacity>
                  {/* <FlatList
                    data={images}
                    renderItem={renderAllImages}
                    keyExtractor={(item, index) => index.toString()}
                  /> */}
                  <ImageViewer imageUrls={zoomImages} style={{
                    flex: 1
                    //transform:[{rotate:'90deg'}]
                  }}
                    index={modalImage}
                    onSwipeDown={() => setModalVisible(false)}
                    onCancel={() => setModalVisible(false)}
                    backgroundColor='#fff'
                  />
                </View>
              </View>
            </Modal>
          </ScrollView>
        </View>
      )}
    </>
  );
};
const styles = StyleSheet.create({
  pagerView: {
    flex: 1,
  },
  container: {
    // height: 300,
  },
  title: {
    fontSize: 14,
    marginHorizontal: 6,
  },
});
export default HomeDetails;
