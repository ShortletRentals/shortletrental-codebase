import React, { useCallback, useContext, useLayoutEffect, useRef, useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  ScrollView,
  Modal,
  TouchableOpacity,
  FlatList,
  TextInput,
  ActivityIndicator,
  Pressable,
  InteractionManager,
  Image,
  ImageBackground,
  TouchableWithoutFeedback,
  Platform,
  StatusBar,
} from 'react-native';
//kkkkkkkkkkkkkkkkkkkkkkkkkkkk 12 june 2023 ------------------update code-------
import { color, width } from '../../styles/colors';
import Images from '../../styles/Images';
import { commonStyles, height } from '../../styles/style';
import CheckBoxs from '../../components/CheckBox';
import ActionSheet from 'react-native-actions-sheet';
import LinearGradient from 'react-native-linear-gradient';
import { SafeAreaView } from 'react-native-safe-area-context';
import { useEffect } from 'react';
import axios from 'axios';
import { SliderBox } from 'react-native-image-slider-box';
import CheckBox from '@react-native-community/checkbox';
import {
  HOME_URL,
  add_To_Favourite,
  User_Profile,
  getOffer,
  getAllProvince,
  getAllCategory,
  getAllServicesApiUse,
  getAllAmenities,
  getAllTypes,
  BASE_URL,
  MAIN_URL,
  getAllProvince1,
  HOME_URL1,
  getCityArea,
} from '../../network/Webconstant';
import StarRating from 'react-native-star-rating';
import { Context as HomeContext } from '../../context/HomeContext';
import { Context as AuthContext } from '../../context/AuthContext';
import { Context as BookingContext } from '../../context/BookingContext';
import { Svg, SvgCssUri, SvgUri, SvgWithCssUri } from 'react-native-svg';
// import Image from 'react-native-fast-image';
import {
  CategoryCardShimmer,
  MatchCardShimmer,
  SliderShimmer,
} from '../../components/Skeleton';
import { Toast } from 'react-native-toast-message/lib/src/Toast';
import CustomImageFlatList from '../../components/CustomImageFlatList';
import {
  useFocusEffect,
  useIsFocused,
  useNavigation,
  useRoute,
} from '@react-navigation/native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import PushNotification from 'react-native-push-notification';
import { Alert } from 'react-native';
import { BackHandler } from 'react-native';
import Carousel, { Pagination } from 'react-native-snap-carousel';
import { createRef } from 'react';
import ReactNativeModal from 'react-native-modal';
import { Dropdown } from 'react-native-element-dropdown';
import { Calendar } from 'react-native-calendars';
import moment from 'moment';
import { useDebounce } from 'use-debounce';
import CheckBoxFilter from '../../components/CheckBoxFilter';
import { useNetInfo } from '@react-native-community/netinfo';
import { ScreenHeight, ScreenWidth } from 'react-native-elements/dist/helpers';
// import Carousel from 'react-native-slick'
// import { Pagination } from 'react-native-snap-carousel';





const Bathroom = [
  {
    id: 1,
    number: 1,
  },
  {
    id: 2,
    number: 2,
  },
  {
    id: 3,
    number: 3,
  },
  {
    id: 4,
    number: 4,
  },
  {
    id: 5,
    number: 5,
  },
];
const Bedroom = [
  {
    id: 1,
    number: 1,
  },
  {
    id: 2,
    number: 2,
  },
  {
    id: 3,
    number: 3,
  },
  {
    id: 4,
    number: 4,
  },
  {
    id: 5,
    number: 5,
  },
];
let Bedrooms = ''
let Bathrooms = ''
const categoryHome = [
  {
    id: 1,
    title: 5,
    url: Images.fiveStar,
  },
  {
    id: 2,
    title: 4,
    url: Images.fourStar
  },
  {
    id: 3,
    title: 3,
    url: Images.threeStar,
  },
  {
    id: 4,
    title: 2,
    url: Images.twoStar,
  },
  {
    id: 5,
    title: 1,
    url: Images.oneStar,
  },
  {
    id: 6,
    title: 0,
    url: Images.noStar,
  },
];

const BedroomsCount = [
  { label: '0', value: '0' },
  { label: '1', value: '1' },
  { label: '2', value: '2' },
  { label: '3', value: '3' },
  { label: '4', value: '4' },
  { label: '5', value: '5' },
  { label: '6', value: '6' },
  { label: '7', value: '7' },
  { label: '8', value: '8' },
  { label: '9', value: '9' },
  { label: '10', value: '10' },
  { label: '11', value: '11' },
  { label: '12', value: '12' },
  { label: '13', value: '13' },
  { label: '14', value: '14' },
  { label: '15', value: '15' },
  { label: '16', value: '16' },
  { label: '17', value: '17' },
  { label: '18', value: '18' },
  { label: '19', value: '19' },
  { label: '20', value: '20' },
];
let myToken = null;

// let userId = ""
let provinceId = '';
let cityId = '';

const Home = props => {
  // 8561028424
  const route = useRoute();
  const ref = useRef(null)
  const [loader, setLoader] = useState(true);
  const [activeSlide, setActiveSlide] = useState(1)
  const [searchLoader, setSearchLoader] = useState(false);
  const [loaderOffer, setLoaderOffer] = useState(false);
  const [starCount, setStarCount] = useState(0);
  const actionSheetRef = useRef(null);
  const [toggleCheckBox, setToggleCheckBox] = useState([]);
  const [typeBox, setTypeBox] = useState('all')
  const [isSelect, setIsSelect] = useState(-1);
  const [toggleCheckBox1, setToggleCheckBox1] = useState('');
  const [select, setSelect] = useState('');
  const [selectBedroom, setSelectBedroom] = useState('');
  const [data, setData] = useState([]);
  const [homeData, setHomeData] = useState([]);
  const [select1, setSelect1] = useState(true);
  const [pageNo, setPageNo] = useState(1);
  const [isListEnd, setListEnd] = useState(false);
  const [bottomLoading, setBottomLoading] = useState(false);
  const [Category, setCategory] = useState([]);
  const [activeIndex, setActivityIndex] = useState(0);
  const [moreListEnd, setMoreListEnd] = useState(false);
  const [moreBottomLoading, setMoreBottomLoading] = useState(false);
  const [morePage, setMorePage] = useState(1)

  const {
    // getAllCategoryApi,
    // getAllServicesApi,
    state: { category, services },
  } = useContext(HomeContext);
  const {
    getProvinceApi,
    getCityApi,
    state: { bookingData, provinceData, cityData, areaData, countryData },
  } = useContext(BookingContext);
  const [unslectCat, setunslectCat] = useState(false);
  const [clearecategory, SetClearecategory] = useState(false);
  const [profileUserData, setProfileUserData] = useState([]);
  const [types, setTypes] = useState('');
  const [modalVisible, setModalVisible] = useState(false);
  const [curIndex, setCurIndex] = useState(0);
  // const [extraServices, setExtraServices] = useState(services)
  const [extraCategory, setExtraCategory] = useState(category);
  const [Bathrooms, setBathRooms] = useState(Bathroom);
  const [BedroomsFilter, setBedroomsFilter] = useState(Bedroom);
  const [imageArr, setImageArr] = useState([]);
  const [offerData, setOfferData] = useState([]);
  const [homeDataNew, setHomeDataNew] = useState([]);
  const [instantBook, setInstantBook] = useState([]);
  const [luxuryBooking, setLuxuryBooking] = useState([]);
  const [moreListing, setMoreListing] = useState([]);
  const [rareBooking, setRareBooking] = useState([]);
  const [province, setProvince] = useState([]);
  const [ProvinceId, setProvinceId] = useState(null);
  const [city, setCity] = useState(null);
  const [date, setDate] = useState('Select Date');
  const [dateLocal, setDateLocal] = useState('Select Date');
  const [bedrooms, setBedrooms] = useState(0);
  const [categoryFilter, setCategoryFilter] = useState([]);
  const [serviceFilter, setServiceFilter] = useState([]);

  const [filterLoader, setFilterLoader] = useState(false);
  const [searchVisiable, setSearchVisiable] = useState(false);
  const [modalDate, setModalDate] = useState(false);
  const [modalCheckOut, setModalCheckOut] = useState(false);
  const carouselRef = createRef();
  const [count1, setCount1] = useState(0);
  const [count2, setCount2] = useState(0);
  const [count3, setCount3] = useState(0);
  const [count4, setCount4] = useState(0);
  const [guestModal, setGuestModal] = useState(false);
  const [isFeatured, setIsFeatured] = useState();
  const [isFeatured1, setIsFeatured1] = useState();
  const [isFeatured2, setIsFeatured2] = useState('');
  const isFocoused = useIsFocused()
  const [dateTrue, setDateTrue] = useState(false)
  const [dateLocalTrue, setDateLocalTrue] = useState(false)
  const [typeData, setTypeData] = useState([])
  const [allProvince, setAllProvince] = useState([])
  const [allCityArea, setAllCityArea] = useState([])

  const [images, setImages] = useState([]);
  const [zoomImages, setZoomImages] = useState([]);
  const [selectNo, setSelectNo] = useState(1)
  const [amenity, setAmenity] = useState('');
  // const [modalVisible, setModalVisible] = useState(false);
  const [modalImage, setModalImage] = useState('')
  const scrollRef = useRef(null)
  const [currentIndex, setCurrentIndex] = useState(0)
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


  useFocusEffect(
    React.useCallback(() => {
      const backAction = () => {
        Alert.alert('Hold on!', 'Are you sure you want to Exit App?', [
          {
            text: 'Cancel',
            onPress: () => null,
            style: 'cancel',
          },
          { text: 'YES', onPress: () => BackHandler.exitApp() },
        ]);
        return true;

      }

      const backHandler = BackHandler.addEventListener(
        'hardwareBackPress',
        backAction,
      );

      return () => backHandler.remove();
    }, [props]))


  PushNotification.configure({
    // (optional) Called when Token is generated (iOS and Android)


    // (required) Called when a remote is received or opened, or local notification is opened
    onNotification: function (notification) {
      // console.log(notification.foreground,'=--=-=-=-=-=');
      // console.log('NOTIFICATION tab bar home--=-==-', notification);
      // Toast.show({ type: 'success', 
      // text1: notification.notification.title,
      // text2:notification.notification.body });


    },

    popInitialNotification: true,
    requestPermissions: true,
  });


  useFocusEffect(
    React.useCallback(() => {

      setHomeData([]);
      setOfferData([]);
      setHomeDataNew([]);
      setInstantBook([]);
      setLuxuryBooking([])
      setRareBooking([])
      setProvince([]);
      setCategory([]);
      setProvinceId('')
      getAllCityAreaApi()
      cityId = ''
      provinceId = ''
      setDate('Select Date')
      setDateLocal('Select Date')

      const task = InteractionManager.runAfterInteractions(async () => {
        const Localtoken = await AsyncStorage.getItem('token_id');
        myToken = Localtoken;

        homePageApi()
        // homePageApiNew()
        getData()

      })

      return () => task.cancel();
    }, [props]),
  );





  const getData = async () => {
    // if (isSelect > 0) {
    //   scrollRef?.current?.scrollToIndex({animated:true, index: 0 })

    // }
    // setHomeData([]);
    // setData([]);
    setListEnd(false);
    setPageNo(1);
    getAllProvinceApi()
    getOfferApi();
    getAllProvinceApiHandler();
    getProvinceApi();
    // getCityApi();
    getAllServicesApi();
    getAllTypesData()
    getAllCategoryApi({ type: "" });



  };

  // useEffect(() => {
  //   if (pageNo > 1 && !isListEnd && !bottomLoading) {
  //     console.log('current page no is' + pageNo, isListEnd);

  //     homePageApi(true);
  //   }
  // }, [pageNo]);
  useEffect(() => {
    // if (morePage > 2 && !moreListEnd && !moreBottomLoading) {
    //   console.log('current more page no is' + morePage, moreListEnd);

    //   // homePageApi(true);
    //   morePageApi(true)
    // }
  }, [morePage]);



  const getAllTypesData = () => {
    try {
      axios({
        method: 'get',
        url: getAllTypes,
        // data,
      }).then(function (response) {
        if (response.data.status == true) {
          let arr = [{
            "id": "all",
            "type": "All",
            "name": "All",
            "image": "https://d1o88e3pxnk9ri.cloudfront.net/uploads/category/DEC2022/1670504381-category.svg",
            "status": 0,
            "created_at": "2022-10-06T07:27:26.000000Z",
            "updated_at": "2022-12-08T12:59:41.000000Z",
            "is_select": true
          }]
          response.data.data.map((item, index) => {
            arr.push({
              "id": item.id,
              "type": item.type,
              "name": item.name,
              "image": item.image,
              "status": item.status,
              "created_at": item.created_at,
              "updated_at": item.updated_at,
              "is_select": false
            })
          })

          setTypeData(arr);
        }
      });
    } catch (error) {
      console.log('error is', error);
    }
  };
  const getAllCategoryApi = (data) => {
    try {
      axios({
        method: 'post',
        url: getAllCategory,
        // data,
      }).then(function (response) {
        if (response.data.status == true) {
          setCategoryFilter(response.data.data);
        }
      });
    } catch (error) {
      console.log('error is', error);
    }
  };

  const getAllServicesApi = () => {
    try {
      axios({
        method: 'get',
        url: getAllAmenities,
        // data,
      }).then(function (response) {
        if (response.data.status == true) {
          setServiceFilter(response.data.data);
        }
      });
    } catch (error) {
      console.log('error is', error);
    }
  };
  const getAllProvinceApi = () => {
    try {
      axios({
        method: 'post',
        url: getAllProvince1,
        // data,
      }).then(function (response) {
        if (response.data.status == true) {
          let temp = response.data.data.map(item => {
            return { value: item.province_id, label: item.name + ' (' + item.total + ')' };
          });
          setAllProvince(temp)
        }
      });
    } catch (error) {
      console.log('error is', error);
    }
  };
  const getAllCityAreaApi = (value) => {
    try {
      axios({
        method: 'post',
        url: getCityArea,
        data: { province_id: value }
      }).then(function (response) {
        if (response.data.status == true) {
          let temp = response.data.data.map(item => {
            return { value: item.province_id, label: item.city_name + '/' + item.name };
          });
          setAllCityArea(temp)
        }
      });
    } catch (error) {
      console.log('error is', error);
    }
  };
  const homePageApi = async isLoadMore => {
    // const tempPageNo = isLoadMore ? pageNo : 1;
    const Localtoken = await AsyncStorage.getItem('token_id');
    const USER_ID = await AsyncStorage.getItem('USER_ID');
    setCategory(categoryHome);
    // setLoader(tempPageNo === 1);
    // setBottomLoading(tempPageNo > 1);
    try {
      setLoader(true)
      await axios({
        method: 'post',
        url: HOME_URL,
        // data: { page: tempPageNo, user_id: USER_ID },
        headers: {
          Authorization: `Bearer ${Localtoken}`,
          Accept: 'application/json',
        },
      }).then(response => {
        // console.log('respponse home api',response.data.data.featuredProperty);
        if (response.data.status == true) {
          setLoader(false)


          setData(response.data.data.category);
          setHomeData(response.data.data.featuredProperty)
          setHomeDataNew(
            response.data.data.superHostProperty
          );
          setInstantBook(response.data.data.rareProperty_book_now)

          setLuxuryBooking(
            response.data.data.luxuryProperty
          );
          setRareBooking(
            response.data.data.rareProperty
          );


          morePageApi(1)


        }
      });
    } catch (error) {
      console.log('errorHomeApi', error);
      setLoader(false);
      setBottomLoading(false);
    }
  };


  const morePageApi = async page => {

    const Localtoken = await AsyncStorage.getItem('token_id');
    const USER_ID = await AsyncStorage.getItem('USER_ID');

    try {
      await axios({
        method: 'post',
        url: HOME_URL1,
        data: { page: page, user_id: USER_ID },
        headers: {
          Authorization: `Bearer ${Localtoken}`,
          Accept: 'application/json',
        },
      }).then(response => {

        setLoader(false);
        // console.log('-=-=-=-=-=-=-=-=-=morepagdata=--=-=-=new-=-=-=',response.data.data);

        if (response.data.status == true) {
          //  let arr=[]
          // response?.data?.data?.data?.map((item,index)=>{
          //         let imgArr = []
          // item?.get_property_images.map((item1, index) => {
          //   // console.log(item1.image);
          //   if (index < 6) {
          //     imgArr.push(item1.image);
          //   }
          // })
          // arr.push({
          //   title:item.title,
          //   address:item.get_property_address,
          //   get_property_images:[],//item.get_property_images,
          //   imgArr:imgArr,
          //   id:item.id,
          //   price:item.price,
          //   avg_rating:item.avg_rating,
          //   total_rating:item.total_rating,
          //   max_guest:item.max_guest,
          //   get_property_bedroom:item.get_property_bedroom  ,
          //   get_user_web:item.get_user_web,
          //   is_fav:item.is_fav
          // })
          // })

          setMoreListing([...moreListing, ...response?.data?.data])

          // setMoreListing(response?.data?.data?.properties?.data)

          // setListEnd(
          //   !!!response?.data?.data?.properties?.data?.length ||
          //   response?.data?.data?.properties?.data?.length < 10,
          // );
          // setMorePage(tempPageNo);
          // // !isLoadMore && setData(response.data.data.category.data);
          // isLoadMore
          //   ?
          //   setMoreListing([...moreListing, ...response?.data?.data?.properties?.data])
          //   : setMoreListing(response?.data?.data?.properties?.data ?? []);
          // // isLoadMore
          // //   ? setData([...data, ...response.data.data.category.data])
          // //   : setData(response.data.data.category.data ?? []);

          // setBottomLoading(false);
        }
      });
    } catch (error) {
      console.log('errorHomeApi', error);
      // setLoader(false);
      // setMoreBottomLoading(false);
    }
  };




  const getOfferApi = async () => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    try {
      setLoaderOffer(true);
      axios({
        method: 'get',
        url: getOffer,
        headers: {
          Accept: 'applicaton/json',
          Authorization: `Bearer ${Localtoken}`,
        },
      }).then(function (response) {

        if (response.data.status === true) {
          setLoaderOffer(false);
          setOfferData(response.data.data);
        }
      });
    } catch (error) {
      console.log('eerrr', error);
      setLoaderOffer(false);
    }
  };

  const getAllProvinceApiHandler = async () => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    try {
      axios({
        method: 'post',
        url: getAllProvince,
        headers: {
          Accept: 'applicaton/json',
          Authorization: `Bearer ${Localtoken}`,
        },
      }).then(function (response) {

        if (response.data.status === true) {
          setProvince(response.data.data);
        }
      });
    } catch (error) {
      console.log('eerrr', error);
    }
  };


  const onclickHandlerFilterApi = async item => {
    setLoader(true);
    setHomeData([]);
    setListEnd(false);
    setPageNo(1);

    await axios({
      method: 'post',
      url: HOME_URL,
      data: { category: item },
    })
      .then(res => {
        if (res.data.status == true) {

          setHomeData(res.data.data.data.featuredProperty);
          setHomeDataNew(res.data.data.data.superHostProperty);
          setInstantBook(res.data.data.data.rareProperty_book_now);
          setLuxuryBooking(res.data.data.data.luxuryProperty);
          setRareBooking(res.data.data.data.rareProperty);

          setLoader(false);

        }
      })
      .catch(e => {
        console.log('error is filter', e);
      });
  };

  const addFavouriteApi = async item => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    const USER_ID = await AsyncStorage.getItem('USER_ID');

    // setLoader(true)
    try {
      setModalVisible(true);
      axios({
        method: 'post',
        url: add_To_Favourite,
        data: { userId: USER_ID, product_id: item },
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${Localtoken}`,
        },
      }).then(function (response) {

        console.log(response.data, "add to fav");
        setModalVisible(false);
        if (response.data.status == true) {
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message,
          });

        } else {

          setModalVisible(false);
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: response.data.message,
          });
        }

        setHomeData(
          homeData.map(prop => {
            if (prop.id == item) prop.is_fav = prop.is_fav ? 0 : 1;
            return prop;
          }),
        );

        setHomeDataNew(
          homeDataNew.map(prop => {
            console.log(prop.id, "props");
            if (prop.id == item) prop.is_fav = prop.is_fav ? 0 : 1;
            return prop;
          }),
        );

        setInstantBook(
          instantBook.map(prop => {
            if (prop.id == item) prop.is_fav = prop.is_fav ? 0 : 1;
            return prop;
          }),
        );
        setLuxuryBooking(
          luxuryBooking.map(prop => {
            if (prop.id == item) prop.is_fav = prop.is_fav ? 0 : 1;
            return prop;
          }),
        );
        setRareBooking(
          rareBooking.map(prop => {
            if (prop.id == item) prop.is_fav = prop.is_fav ? 0 : 1;
            return prop;
          }),
        );
        setMoreListing(
          moreListing.map(prop => {
            if (prop.id == item) prop.is_fav = prop.is_fav ? 0 : 1;
            return prop;
          }),
        );
      });
    } catch (error) {
      console.log('error is add to favorite home', error);
    }
  };

  const onStarRatingPress = rating => {
    setStarCount(rating);
  };

  const homeSearchApiHandlerWithModal = async () => {
    const Localtoken = await AsyncStorage.getItem('token_id');
    setSearchLoader(true);
    try {
      await axios({
        method: 'post',
        url: HOME_URL,
        data: {
          province_id: provinceId,
          city_id: cityId,
          max_guest: count1 + count2 + count3,
          checkIn: moment(date).format('YYYY-MM-DD'),
          checkOut: dateLocal,
          no_of_bedrooms: bedrooms,
        },
        headers: {
          Authorization: `Bearer ${Localtoken}`,
          Accept: 'application/json',
        },
      }).then(response => {

        if (response.data.status == true) {
          setSearchLoader(false);

          setHomeData(response.data.data.data.featuredProperty);
          setHomeDataNew(response.data.data.data.superHostProperty);
          setInstantBook(response.data.data.data.rareProperty_book_now);
          setLuxuryBooking(response.data.data.data.luxuryProperty);
          setRareBooking(response.data.data.data.rareProperty);
          setSearchVisiable(false);
          setCount1(0);
          setCount2(0);
          setCount3(0);
          setCount4(0);
          setBedrooms(0);
        }
      });
    } catch (error) {
      console.log('errorHomeApi', error);
      setSearchLoader(false);
    }
  };
  const renderItem = ({ item, index }) => {
    // console.log('---------isselect', isSelect);
    return (
      <TouchableOpacity
        activeOpacity={0.9}
        key={index}

        onPress={() => {
          // onclickHandlerFilterApi(item.id)

          props?.navigation?.navigate('Homeview', { category: item.id })
            , setIsSelect(index)
          // scrollRef?.current?.scrollToIndex({ index: isSelect })


        }}
      >
        <LinearGradient
          style={[
            commonStyles.flatView,
            {
              height: 70,
              // width: 120,
              minWidth: 100,
              margin: 4,
              borderColor:
                isSelect !== index ? 'transparent' : 'white', borderWidth: 2
            }
          ]}
          colors={[color.appOrangeColor, color.appYellowColor]}>
          {
            item.image.match('svg') == 'svg' ?

              <Svg height={Platform.OS == 'android' ? 25 : 20} width={Platform.OS == 'android' ? 25 : 20} viewBox={Platform.OS == 'android' ? "0 0 30 25" : '0 0 20 20'}>
                <SvgUri
                  width="80%"
                  height="80%"
                  uri={item?.image}

                />
              </Svg>
              :
              <Image source={{ uri: item?.image }} style={{ tintColor: 'white', height: 20, width: 20, resizeMode: 'contain' }} />
          }

          <Text
            style={[
              commonStyles.flatText, { fontSize: 10 }
              // { color: isSelect !== index ? '#000' : '#fff' },
            ]}>
            {item?.name}
          </Text>
        </LinearGradient>
      </TouchableOpacity>
    );
  };


  const addtoFavoriteHandler = async item => {
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
      addFavouriteApi(item), setModalVisible(true);
    }
  };
  const pagination = () => {
    return (
      <Pagination
        dotsLength={homeData?.length}
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
  // const checkBoxClick=(index)=>{
  //   let temmp=[...typeData];
  //   temmp[index].is_select=!temmp[index].is_select
  //   setTypeData([...temmp])
  //   console.log(typeData)
  // }
  const renderItem1 = ({ item, index }) => {
    // console.log('--------renderiterm1', item.is_fav);
    let imgArr = []
    // item?.get_property_images.map((item1, index) => {
    //   if (index < 6) {
    //     imgArr.push(item1.image);
    //   }
    // })
    let arr = []
    arr.push(item.image)
    item.get_property_images.length > 1 &&
      item.get_property_images?.map(img => {
        arr.push(img?.image)
      }
      )
    return (
      <View

        style={{ marginHorizontal: 10, marginBottom: 10, marginTop: 10 }}
        // activeOpacity={0.5}
        key={item.id}>
        <View>
          <View
            style={styles.flatlistHeader}>
            {item?.featured == 'Yes' ? (
              <Image
                source={Images.featureImage}
                style={styles.featureImage}
              />
            ) : null}

            <View
            // style={{borderWidth:1,borderTopLeftRadius:4,borderTopRightRadius:4,borderColor:color.inputBoxBorderGray}}
            >
              <CustomImageFlatList
                data={
                  item.get_property_images.length > 1 ?
                    arr


                    :
                    ["https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60",
                      "https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60"]}
              />

            </View>
            <TouchableOpacity
              style={{
                // paddingHorizontal: width * (10 / 375),

                // width: width * (332 / 375),
                paddingHorizontal: width * (10 / 375),
                marginBottom: width * (1 / 375),
                borderColor: '#E9E9E9',
                borderWidth: 1,
                borderBottomLeftRadius: 10,
                borderBottomRightRadius: 10,
                backgroundColor: '#FFFFFF',
                width: width * (332 / 375),
              }}
              onPress={async () => {
                props.navigation.navigate('HomeDetails', {
                  propertyid: item.id,
                  price: item.price,
                }),
                  await AsyncStorage.setItem(
                    'Price',
                    JSON.stringify(item.price),
                  );
              }}
            >
              <Text
                numberOfLines={1}
                style={styles.headertitle}>
                {item.title}
              </Text>
              <View
                style={{
                  flexDirection: 'row',
                  justifyContent: 'space-between',
                  width: width * (300 / 375),
                }}>
                <View
                  style={{
                    flexDirection: 'row',
                    width: width * (145 / 375),
                    marginTop: 8,
                    justifyContent: 'center',
                    alignItems: 'center',
                    marginHorizontal: 20,
                  }}>
                  <TouchableOpacity style={{}}>
                    <Image
                      resizeMode="contain"
                      source={Images.LocationIcons}
                      style={{
                        width: 20,
                        height: 20,
                        alignSelf: 'center',
                      }}
                    />
                  </TouchableOpacity>
                  <View style={{
                    marginHorizontal: 2, width: width / 2 - 30
                  }}>
                    {
                      item?.get_property_address?.map(add => {
                        // console.log('=========adddddddddd addresss',add.get_property_area.name,add.get_property_city.name);
                        return (
                          <Text
                            numberOfLines={3}
                            style={{ color: '#393939', marginHorizontal: 6 }}>
                            {add?.get_property_area?.name + ' - ' + add?.get_property_city?.name}
                          </Text>
                        )
                      })
                    }

                  </View>
                </View>
                <View
                  style={{
                    flexDirection: 'row',
                    width: width * (70 / 375),
                    marginTop: 10,
                    justifyContent: 'flex-end',
                    alignItems: 'center',
                  }}>
                  <TouchableOpacity>
                    <Image
                      resizeMode="contain"
                      source={Images.StartIcon}
                      style={{
                        paddingLeft: 10,
                        width: 16,
                        height: 16,
                      }}
                    />
                  </TouchableOpacity>
                  <Text style={{ color: color.primaryColorBlack, marginLeft: 5 }}>

                    {item.avg_rating == null ? 0.0 : item.avg_rating}({item.total_rating == null ? 0 : item.total_rating})
                  </Text>
                </View>
              </View>
              <View style={{ flexDirection: 'row' }}>
                <Text
                  style={{
                    marginTop: 4,
                    fontWeight: '500',
                    color: color.primaryColorBlack,
                  }}>
                  {' '}
                  NGN {item.price}
                </Text>
                <Text
                  style={{
                    marginTop: 4,
                    color: '#393939',
                    marginHorizontal: 6,
                  }}>
                  Night
                </Text>
              </View>

              <View
                style={{
                  flexDirection: 'row',
                  justifyContent: 'space-between',
                  marginBottom: 10,
                }}>
                <View
                  style={{
                    flexDirection: 'row',
                    width: width * (130 / 375),
                    height: 40,
                    marginTop: 4,
                    justifyContent: 'space-between',
                  }}>
                  <View
                    style={{
                      flexDirection: 'row',
                      width: width * (60 / 375),
                      marginTop: 4,
                      borderRadius: width * (8 / 375),
                      borderWidth: 1,
                      borderColor: color.appTextColor,
                      justifyContent: 'center',
                      alignItems: 'center',
                    }}>
                    <View>
                      <Image
                        resizeMode="contain"
                        source={Images.GroupIcons}
                        // source={{ uri: 'C:/Users/Admin/Downloads/Bed.svg' }}
                        style={{
                          paddingLeft: 10,
                          width: 20,
                          height: 20,
                        }}
                      />
                    </View>
                    <View>
                      <Text style={{ paddingLeft: 10, color: '#000' }}>
                        {item.max_guest}
                      </Text>
                    </View>
                  </View>

                  <View
                    style={{
                      flexDirection: 'row',
                      width: width * (60 / 375),
                      marginTop: 4,
                      borderRadius: width * (8 / 375),
                      borderWidth: 1,
                      borderColor: color.appTextColor,
                      justifyContent: 'center',
                      alignItems: 'center',
                    }}>
                    <View>
                      <Image
                        resizeMode="contain"
                        source={Images.BedIcons}
                        style={{
                          paddingLeft: 10,
                          width: 20,
                          height: 20,
                        }}
                      />
                    </View>
                    <View>
                      <Text style={{ paddingLeft: 10, color: '#000' }}>
                        {item?.get_property_bedroom?.map(
                          bed => bed.no_of_bedrooms,
                        )}
                      </Text>
                    </View>
                  </View>
                </View>
                {item?.get_user_web?.is_super_host == 'Yes' ? (
                  <View
                    style={{
                      backgroundColor: color.appBlueColor,
                      height: 40,
                      width: 40,
                      borderRadius: 20,
                      alignItems: 'center',
                      justifyContent: 'center',
                    }}>
                    <Image
                      resizeMode="contain"
                      source={Images.SeekLogoIcons}
                      style={{
                        width: 20,
                        height: 20,
                        justifyContent: 'center',
                        alignContent: 'center',
                      }}
                    />
                  </View>
                ) : null}
              </View>
            </TouchableOpacity>
          </View>
          <View
            style={{
              alignSelf: 'flex-end',
              marginHorizontal: 10,
              zIndex: 1000,
              position: 'absolute',
              right: 5,
              marginTop: 10,
            }}>
            <TouchableOpacity onPress={() => addtoFavoriteHandler(item.id)}>
              <Image
                source={item.is_fav === 1 ? Images.heart : Images.heartIcon}
                style={{ height: 24, width: 24 }}
                resizeMode="contain"
              />
            </TouchableOpacity>
          </View>
        </View>
      </View >
    );
  };
  const renderItem2 = ({ item, index }) => {
    let arr = []
    arr.push(item.image)
    item.get_property_images.length > 1 &&
      item.get_property_images?.map(img => {
        arr.push(img?.image)
      }
      )

    return (
      <View

        style={{ marginHorizontal: 10, marginBottom: 10, marginTop: 10 }}
        // activeOpacity={0.5}
        key={item.id}>
        <View
          style={[styles.flatlistHeader, {
            marginBottom: width * (4 / 375)
          }]}>
          <Image source={Images.supertag}
            style={styles.featureImage} />
          <View>
            <CustomImageFlatList
              data={item?.get_property_images.length > 1 ? arr :
                ["https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60",
                  "https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60"]}
            />

          </View>

          <TouchableOpacity
            onPress={async () => {
              props.navigation.navigate('HomeDetails', {
                propertyid: item.id,
                price: item.price,
              }),
                await AsyncStorage.setItem('Price', JSON.stringify(item.price));
            }}
            style={{
              paddingHorizontal: width * (10 / 375),
              marginBottom: width * (1 / 375),
              borderColor: '#E9E9E9',
              borderWidth: 1,
              borderBottomLeftRadius: 10,
              borderBottomRightRadius: 10,
              backgroundColor: '#FFFFFF',
              width: width * (332 / 375),

            }}>
            <Text
              numberOfLines={1}
              style={{
                marginTop: 5,
                fontSize: 16,
                fontWeight: '400',
                color: color.primaryColorBlack,
              }}>
              {item.title}
            </Text>
            <View
              style={{
                flexDirection: 'row',
                justifyContent: 'space-between',
                width: width * (300 / 375),
              }}>
              <View
                style={{
                  flexDirection: 'row',
                  width: width * (145 / 375),
                  marginTop: 8,
                  justifyContent: 'center',
                  alignItems: 'center',
                  marginHorizontal: 20,
                }}>
                <TouchableOpacity style={{}}>
                  <Image
                    resizeMode="contain"
                    source={Images.LocationIcons}
                    style={{
                      // paddingLeft: 10,
                      width: 20,
                      height: 20,
                      alignSelf: 'center',
                    }}
                  />
                </TouchableOpacity>
                <View style={{ marginHorizontal: 2, width: width / 2 - 30 }}>
                  <Text
                    numberOfLines={2}
                    style={{ color: '#393939', marginHorizontal: 6 }}>
                    {item?.get_property_address?.map(add => add?.get_property_area?.name + ' - ' + add?.get_property_city?.name)}

                  </Text>
                </View>
              </View>
              <View
                style={{
                  flexDirection: 'row',
                  width: width * (70 / 375),
                  marginTop: 10,
                  justifyContent: 'flex-end',
                  alignItems: 'center',
                }}>
                <TouchableOpacity>
                  <Image
                    resizeMode="contain"
                    source={Images.StartIcon}
                    style={{
                      paddingLeft: 10,
                      width: 16,
                      height: 16,
                    }}
                  />
                </TouchableOpacity>
                <Text style={{ color: color.primaryColorBlack, marginLeft: 5 }}>

                  {item.avg_rating == null ? 0.0 : item.avg_rating}({item.total_rating == null ? 0 : item.total_rating})
                </Text>
              </View>
            </View>
            <View style={{ flexDirection: 'row' }}>
              <Text
                style={{
                  marginTop: 4,
                  fontWeight: '500',
                  color: color.primaryColorBlack,
                }}>
                NGN {item.price}
              </Text>
              <Text
                style={{ marginTop: 4, color: '#393939', marginHorizontal: 12 }}>
                Night
              </Text>
            </View>

            <View
              style={{
                flexDirection: 'row',
                justifyContent: 'space-between',
                marginBottom: 10,
              }}>
              <View
                style={{
                  flexDirection: 'row',
                  width: width * (130 / 375),
                  height: 40,
                  marginTop: 4,
                  justifyContent: 'space-between',
                }}>
                <View
                  style={{
                    flexDirection: 'row',
                    width: width * (60 / 375),
                    marginTop: 4,
                    borderRadius: width * (8 / 375),
                    borderWidth: 1,
                    borderColor: color.appTextColor,
                    justifyContent: 'center',
                    alignItems: 'center',
                  }}>
                  <View>
                    <Image
                      resizeMode="contain"
                      source={Images.GroupIcons}
                      // source={{ uri: 'C:/Users/Admin/Downloads/Bed.svg' }}
                      style={{
                        paddingLeft: 10,
                        width: 20,
                        height: 20,
                      }}
                    />
                  </View>
                  <View>
                    <Text style={{ paddingLeft: 10, color: '#000' }}>
                      {item.max_guest}
                    </Text>
                  </View>
                </View>

                <View
                  style={{
                    flexDirection: 'row',
                    width: width * (60 / 375),
                    marginTop: 4,
                    borderRadius: width * (8 / 375),
                    borderWidth: 1,
                    borderColor: color.appTextColor,
                    justifyContent: 'center',
                    alignItems: 'center',
                  }}>
                  <View>
                    <Image
                      resizeMode="contain"
                      source={Images.BedIcons}
                      style={{
                        paddingLeft: 10,
                        width: 20,
                        height: 20,
                      }}
                    />
                  </View>
                  <View>
                    <Text style={{ paddingLeft: 10, color: '#000' }}>
                      {item?.get_property_bedroom?.map(
                        bed => bed.no_of_bedrooms,
                      )}
                    </Text>
                  </View>
                </View>
              </View>
              {item?.get_user_web?.is_super_host == 'Yes' ? (
                <View
                  style={{
                    backgroundColor: color.appBlueColor,
                    height: 40,
                    width: 40,
                    borderRadius: 20,
                    alignItems: 'center',
                    justifyContent: 'center',
                  }}>
                  <Image
                    resizeMode="contain"
                    source={Images.SeekLogoIcons}
                    style={{
                      width: 20,
                      height: 20,
                      justifyContent: 'center',
                      alignContent: 'center',
                    }}
                  />
                </View>
              ) : null}
            </View>
          </TouchableOpacity>
        </View>
        <View
          style={{
            alignSelf: 'flex-end',
            marginHorizontal: 10,
            zIndex: 1000,
            position: 'absolute',
            right: 5,
            marginTop: 10,
          }}>
          <TouchableOpacity onPress={() => addtoFavoriteHandler(item.id)}>
            <Image
              source={item.is_fav === 1 ? Images.heart : Images.heartIcon}
              style={{ height: 24, width: 24 }}
              resizeMode="contain"
            />
          </TouchableOpacity>
        </View>
      </View>
    );
  };

  const renderItem3 = ({ item, index }) => {
    let arr = []
    arr.push(item.image)
    item.get_property_images.length > 1 &&
      item.get_property_images?.map(img => {
        arr.push(img?.image)
      }
      )
    let title = item.title.split('|')

    return (
      <View

        style={{ marginBottom: 10, marginHorizontal: 10, marginTop: 10 }}
        // activeOpacity={0.5}
        key={item.id}>
        {item.book_type == 'Book_now' ? (
          <View>
            <View
              style={[styles.flatlistHeader, {

                marginBottom: width * (4 / 375),

              }]}>

              {item?.featured == 'Yes' ? (
                <Image
                  source={Images.featureImage}
                  style={styles.featureImage}
                />
              ) : null}
              {/* <Image
                source={{ uri: item?.image }}
                style={{
                  borderTopLeftRadius: 12,
                  borderTopRightRadius: 12,
                  width: width * (331.8 / 375),
                  alignSelf: 'center',
                  height: width * (155 / 375),
                }}
              /> */}
              <View>
                <CustomImageFlatList
                  data={item?.get_property_images.length > 1 ? arr :
                    ["https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60",
                      "https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60"]}
                />

              </View>

              <TouchableOpacity
                onPress={async () => {
                  props.navigation.navigate('HomeDetails', {
                    propertyid: item.id,
                    price: item.price,
                  }),
                    await AsyncStorage.setItem(
                      'Price',
                      JSON.stringify(item.price),
                    );
                }}
                style={{
                  paddingHorizontal: width * (10 / 375),
                  marginBottom: width * (1 / 375),
                  borderColor: '#E9E9E9',
                  borderWidth: 1,
                  // marginBottom: 10,
                  borderBottomLeftRadius: 10,
                  borderBottomRightRadius: 10,
                  // marginHorizontal: width * (10 / 375),
                  backgroundColor: '#FFFFFF',
                  width: width * (332 / 375),
                  // height: width * (145 / 375)
                  // height: 151
                }}>
                <Text
                  numberOfLines={1}
                  style={{
                    marginTop: 5,
                    fontSize: 16,
                    fontWeight: '400',
                    color: color.primaryColorBlack,
                  }}>
                  {item.title}
                </Text>
                <View
                  style={{
                    flexDirection: 'row',
                    justifyContent: 'space-between',
                    width: width * (300 / 375),
                  }}>
                  <View
                    style={{
                      flexDirection: 'row',
                      width: width * (145 / 375),
                      marginTop: 8,
                      justifyContent: 'center',
                      alignItems: 'center',
                      marginHorizontal: 20,
                    }}>
                    <TouchableOpacity style={{}}>
                      <Image
                        resizeMode="contain"
                        source={Images.LocationIcons}
                        style={{
                          // paddingLeft: 10,
                          width: 20,
                          height: 20,
                          alignSelf: 'center',
                        }}
                      />
                    </TouchableOpacity>
                    <View style={{ marginHorizontal: 2, width: width / 2 - 30 }}>
                      <Text
                        numberOfLines={2}
                        style={{ color: '#393939', marginHorizontal: 6 }}>
                        {/* {item?.get_property_address?.map(add => add.address)} */}
                        {/* {title[1]} */}
                        {item?.get_property_address?.map(add => add?.get_property_area?.name + ' - ' + add?.get_property_city?.name)}

                      </Text>
                    </View>
                  </View>
                  <View
                    style={{
                      flexDirection: 'row',
                      width: width * (70 / 375),
                      marginTop: 10,
                      justifyContent: 'flex-end',
                      alignItems: 'center',
                    }}>
                    <TouchableOpacity>
                      <Image
                        resizeMode="contain"
                        source={Images.StartIcon}
                        style={{
                          paddingLeft: 10,
                          width: 16,
                          height: 16,
                        }}
                      />
                    </TouchableOpacity>
                    <Text style={{ color: color.primaryColorBlack, marginLeft: 5 }}>
                      {' '}
                      {item.avg_rating == null ? 0.0 : item.avg_rating}({item.total_rating == null ? 0 : item.total_rating})
                    </Text>
                  </View>
                </View>
                <View style={{ flexDirection: 'row' }}>
                  <Text
                    style={{
                      marginTop: 4,
                      fontWeight: '500',
                      color: color.primaryColorBlack,
                    }}>
                    {' '}
                    NGN {item.price}
                  </Text>
                  <Text
                    style={{
                      marginTop: 4,
                      color: '#393939',
                      marginHorizontal: 12,
                    }}>
                    Night
                  </Text>
                </View>

                <View
                  style={{
                    flexDirection: 'row',
                    justifyContent: 'space-between',
                    marginBottom: 10,
                  }}>
                  <View
                    style={{
                      flexDirection: 'row',
                      width: width * (130 / 375),
                      height: 40,
                      marginTop: 4,
                      justifyContent: 'space-between',
                    }}>
                    <View
                      style={{
                        flexDirection: 'row',
                        width: width * (60 / 375),
                        marginTop: 4,
                        borderRadius: width * (8 / 375),
                        borderWidth: 1,
                        borderColor: color.appTextColor,
                        justifyContent: 'center',
                        alignItems: 'center',
                      }}>
                      <View>
                        <Image
                          resizeMode="contain"
                          source={Images.GroupIcons}
                          // source={{ uri: 'C:/Users/Admin/Downloads/Bed.svg' }}
                          style={{
                            paddingLeft: 10,
                            width: 20,
                            height: 20,
                          }}
                        />
                      </View>
                      <View>
                        <Text style={{ paddingLeft: 10, color: '#000' }}>
                          {item.max_guest}
                        </Text>
                      </View>
                    </View>

                    <View
                      style={{
                        flexDirection: 'row',
                        width: width * (60 / 375),
                        marginTop: 4,
                        borderRadius: width * (8 / 375),
                        borderWidth: 1,
                        borderColor: color.appTextColor,
                        justifyContent: 'center',
                        alignItems: 'center',
                      }}>
                      <View>
                        <Image
                          resizeMode="contain"
                          source={Images.BedIcons}
                          style={{
                            paddingLeft: 10,
                            width: 20,
                            height: 20,
                          }}
                        />
                      </View>
                      <View>
                        <Text style={{ paddingLeft: 10, color: '#000' }}>
                          {item?.get_property_bedroom?.map(
                            bed => bed.no_of_bedrooms,
                          )}
                        </Text>
                      </View>
                    </View>
                  </View>
                  {item?.get_user_web?.is_super_host == 'Yes' ? (
                    <View
                      style={{
                        backgroundColor: color.appBlueColor,
                        height: 40,
                        width: 40,
                        borderRadius: 20,
                        alignItems: 'center',
                        justifyContent: 'center',
                      }}>
                      <Image
                        resizeMode="contain"
                        source={Images.SeekLogoIcons}
                        style={{
                          width: 20,
                          height: 20,
                          justifyContent: 'center',
                          alignContent: 'center',
                        }}
                      />
                    </View>
                  ) : null}
                </View>
              </TouchableOpacity>
            </View>
            <View
              style={{
                alignSelf: 'flex-end',
                marginHorizontal: 10,
                zIndex: 1000,
                position: 'absolute',
                right: 5,
                marginTop: 10,
              }}>
              <TouchableOpacity onPress={() => addtoFavoriteHandler(item.id)}>
                <Image
                  source={item.is_fav === 1 ? Images.heart : Images.heartIcon}
                  style={{ height: 24, width: 24 }}
                  resizeMode="contain"
                />
              </TouchableOpacity>
            </View>
          </View>
        ) : null
        }
      </View >
    );
  };

  const renderItem4 = ({ item, index }) => {
    let arr = []
    arr.push(item.image)
    item.get_property_images.length > 1 &&
      item.get_property_images?.map(img => {
        arr.push(img?.image)
      }
      )
    return (
      <View

        style={{ marginBottom: 10, marginHorizontal: 10, marginTop: 10 }}
        // activeOpacity={0.5}
        key={item.id}>
        {item.luxury == 'Yes' ? (
          <View>
            <View
              style={styles.flatlistHeader}>

              {item?.luxury == 'Yes' ? (
                <Image
                  source={Images.luxurytag}
                  resizeMode='contain'
                  style={styles.featureImage}
                />
              ) : null}

              <View>
                <CustomImageFlatList
                  data={item?.get_property_images.length > 1 ? arr :
                    ["https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60",
                      "https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60"]}
                />

              </View>

              <TouchableOpacity
                onPress={async () => {
                  props.navigation.navigate('HomeDetails', {
                    propertyid: item.id,
                    price: item.price,
                  }),
                    await AsyncStorage.setItem(
                      'Price',
                      JSON.stringify(item.price),
                    );
                }}
                style={{
                  paddingHorizontal: width * (10 / 375),
                  marginBottom: width * (1 / 375),
                  borderColor: '#E9E9E9',
                  borderWidth: 1,
                  borderBottomLeftRadius: 10,
                  borderBottomRightRadius: 10,
                  backgroundColor: '#FFFFFF',
                  width: width * (332 / 375),

                }}>
                <Text
                  numberOfLines={1}
                  style={{
                    marginTop: 5,
                    fontSize: 16,
                    fontWeight: '400',
                    color: color.primaryColorBlack,
                  }}>
                  {item.title}
                </Text>
                <View
                  style={{
                    flexDirection: 'row',
                    justifyContent: 'space-between',
                    width: width * (300 / 375),
                  }}>
                  <View
                    style={{
                      flexDirection: 'row',
                      width: width * (145 / 375),
                      marginTop: 8,
                      justifyContent: 'center',
                      alignItems: 'center',
                      marginHorizontal: 20,
                    }}>
                    <TouchableOpacity style={{}}>
                      <Image
                        resizeMode="contain"
                        source={Images.LocationIcons}
                        style={{
                          // paddingLeft: 10,
                          width: 20,
                          height: 20,
                          alignSelf: 'center',
                        }}
                      />
                    </TouchableOpacity>
                    <View style={{ marginHorizontal: 2, width: width / 2 - 30 }}>
                      <Text
                        numberOfLines={2}
                        style={{ color: '#393939', marginHorizontal: 6 }}>

                        {item?.get_property_address?.map(add => add?.get_property_area?.name + ' - ' + add?.get_property_city?.name)}


                      </Text>
                    </View>
                  </View>
                  <View
                    style={{
                      flexDirection: 'row',
                      width: width * (70 / 375),
                      marginTop: 10,
                      justifyContent: 'flex-end',
                      alignItems: 'center',
                    }}>
                    <TouchableOpacity>
                      <Image
                        resizeMode="contain"
                        source={Images.StartIcon}
                        style={{
                          paddingLeft: 10,
                          width: 16,
                          height: 16,
                        }}
                      />
                    </TouchableOpacity>
                    <Text style={{ color: color.primaryColorBlack, marginLeft: 5 }}>

                      {item.avg_rating == null ? 0.0 : item.avg_rating}({item.total_rating == null ? 0 : item.total_rating})
                    </Text>
                  </View>
                </View>
                <View style={{ flexDirection: 'row' }}>
                  <Text
                    style={{
                      marginTop: 4,
                      fontWeight: '500',
                      color: color.primaryColorBlack,
                    }}>
                    {' '}
                    NGN {item.price}
                  </Text>
                  <Text
                    style={{
                      marginTop: 4,
                      color: '#393939',
                      marginHorizontal: 12,
                    }}>
                    Night
                  </Text>
                </View>

                <View
                  style={{
                    flexDirection: 'row',
                    justifyContent: 'space-between',
                    marginBottom: 10,
                  }}>
                  <View
                    style={{
                      flexDirection: 'row',
                      width: width * (130 / 375),
                      height: 40,
                      marginTop: 4,
                      justifyContent: 'space-between',
                    }}>
                    <View
                      style={{
                        flexDirection: 'row',
                        width: width * (60 / 375),
                        marginTop: 4,
                        borderRadius: width * (8 / 375),
                        borderWidth: 1,
                        borderColor: color.appTextColor,
                        justifyContent: 'center',
                        alignItems: 'center',
                      }}>
                      <View>
                        <Image
                          resizeMode="contain"
                          source={Images.GroupIcons}
                          style={{
                            paddingLeft: 10,
                            width: 20,
                            height: 20,
                          }}
                        />
                      </View>
                      <View>
                        <Text style={{ paddingLeft: 10, color: '#000' }}>
                          {item.max_guest}
                        </Text>
                      </View>
                    </View>

                    <View
                      style={{
                        flexDirection: 'row',
                        width: width * (60 / 375),
                        marginTop: 4,
                        borderRadius: width * (8 / 375),
                        borderWidth: 1,
                        borderColor: color.appTextColor,
                        justifyContent: 'center',
                        alignItems: 'center',
                      }}>
                      <View>
                        <Image
                          resizeMode="contain"
                          source={Images.BedIcons}
                          style={{
                            paddingLeft: 10,
                            width: 20,
                            height: 20,
                          }}
                        />
                      </View>
                      <View>
                        <Text style={{ paddingLeft: 10, color: '#000' }}>
                          {item?.get_property_bedroom?.map(
                            bed => bed.no_of_bedrooms,
                          )}
                        </Text>
                      </View>
                    </View>
                  </View>
                  {item?.get_user_web?.is_super_host == 'Yes' ? (
                    <View
                      style={{
                        backgroundColor: color.appBlueColor,
                        height: 40,
                        width: 40,
                        borderRadius: 20,
                        alignItems: 'center',
                        justifyContent: 'center',
                      }}>
                      <Image
                        resizeMode="contain"
                        source={Images.SeekLogoIcons}
                        style={{
                          width: 20,
                          height: 20,
                          justifyContent: 'center',
                          alignContent: 'center',
                        }}
                      />
                    </View>
                  ) : null}
                </View>
              </TouchableOpacity>
            </View>
            <View
              style={{
                alignSelf: 'flex-end',
                marginHorizontal: 10,
                zIndex: 1000,
                position: 'absolute',
                right: 5,
                marginTop: 10,
              }}>
              <TouchableOpacity onPress={() => addtoFavoriteHandler(item.id)}>
                <Image
                  source={item.is_fav === 1 ? Images.heart : Images.heartIcon}
                  style={{ height: 24, width: 24 }}
                  resizeMode="contain"
                />
              </TouchableOpacity>
            </View>
          </View>
        ) : null
        }
      </View >
    );
  };
  const renderItem5 = ({ item, index }) => {

    let arr = []
    arr.push(item.image)
    item.get_property_images.length > 1 &&
      item.get_property_images?.map(img => {
        arr.push(img?.image)
      }
      )


    return (
      <View

        style={{ marginBottom: 10, marginHorizontal: 10, marginTop: 10 }}
        // activeOpacity={0.5}
        key={item.id}>
        {item.rare == 'Yes' ? (
          <View>
            <View
              style={styles.flatlistHeader}>

              <Image
                source={Images.raretag}
                style={styles.featureImage}
              />

              <View>
                <CustomImageFlatList
                  data={item?.get_property_images.length > 1 ? arr :
                    ["https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60",
                      "https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60"]}
                />

              </View>

              <TouchableOpacity
                onPress={async () => {
                  props.navigation.navigate('HomeDetails', {
                    propertyid: item.id,
                    price: item.price,
                  }),
                    await AsyncStorage.setItem(
                      'Price',
                      JSON.stringify(item.price),
                    );
                }}
                style={{
                  paddingHorizontal: width * (10 / 375),
                  marginBottom: width * (1 / 375),
                  borderColor: '#E9E9E9',
                  borderWidth: 1,
                  // marginBottom: 10,
                  borderBottomLeftRadius: 10,
                  borderBottomRightRadius: 10,
                  // marginHorizontal: width * (10 / 375),
                  backgroundColor: '#FFFFFF',
                  width: width * (332 / 375),
                  // height: width * (145 / 375)
                  // height: 151
                }}>
                <Text
                  numberOfLines={1}
                  style={{
                    marginTop: 5,
                    fontSize: 16,
                    fontWeight: '400',
                    color: color.primaryColorBlack,
                  }}>
                  {item.title}
                </Text>
                <View
                  style={{
                    flexDirection: 'row',
                    justifyContent: 'space-between',
                    width: width * (300 / 375),
                  }}>
                  <View
                    style={{
                      flexDirection: 'row',
                      width: width * (145 / 375),
                      marginTop: 8,
                      justifyContent: 'center',
                      alignItems: 'center',
                      marginHorizontal: 20,
                    }}>
                    <TouchableOpacity style={{}}>
                      <Image
                        resizeMode="contain"
                        source={Images.LocationIcons}
                        style={{
                          // paddingLeft: 10,
                          width: 20,
                          height: 20,
                          alignSelf: 'center',
                        }}
                      />
                    </TouchableOpacity>
                    <View style={{ marginHorizontal: 2, width: width / 2 - 30 }}>
                      <Text
                        numberOfLines={2}
                        style={{ color: '#393939', marginHorizontal: 6 }}>
                        {/* {item?.get_property_address?.map(add => add.address)} */}
                        {/* {title[1]} */}
                        {item?.get_property_address?.map(add => add?.get_property_area?.name + ' - ' + add?.get_property_city?.name)}

                      </Text>
                    </View>
                  </View>
                  <View
                    style={{
                      flexDirection: 'row',
                      width: width * (70 / 375),
                      marginTop: 10,
                      justifyContent: 'flex-end',
                      alignItems: 'center',
                    }}>
                    <TouchableOpacity>
                      <Image
                        resizeMode="contain"
                        source={Images.StartIcon}
                        style={{
                          paddingLeft: 10,
                          width: 16,
                          height: 16,
                        }}
                      />
                    </TouchableOpacity>
                    <Text style={{ color: color.primaryColorBlack, marginLeft: 5 }}>
                      {' '}
                      {item.avg_rating == null ? 0.0 : item.avg_rating}({item.total_rating == null ? 0 : item.total_rating})
                    </Text>
                  </View>
                </View>
                <View style={{ flexDirection: 'row' }}>
                  <Text
                    style={{
                      marginTop: 4,
                      fontWeight: '500',
                      color: color.primaryColorBlack,
                    }}>
                    {' '}
                    NGN {item.price}
                  </Text>
                  <Text
                    style={{
                      marginTop: 4,
                      color: '#393939',
                      marginHorizontal: 12,
                    }}>
                    Night
                  </Text>
                </View>

                <View
                  style={{
                    flexDirection: 'row',
                    justifyContent: 'space-between',
                    marginBottom: 10,
                  }}>
                  <View
                    style={{
                      flexDirection: 'row',
                      width: width * (130 / 375),
                      height: 40,
                      marginTop: 4,
                      justifyContent: 'space-between',
                    }}>
                    <View
                      style={{
                        flexDirection: 'row',
                        width: width * (60 / 375),
                        marginTop: 4,
                        borderRadius: width * (8 / 375),
                        borderWidth: 1,
                        borderColor: color.appTextColor,
                        justifyContent: 'center',
                        alignItems: 'center',
                      }}>
                      <View>
                        <Image
                          resizeMode="contain"
                          source={Images.GroupIcons}
                          // source={{ uri: 'C:/Users/Admin/Downloads/Bed.svg' }}
                          style={{
                            paddingLeft: 10,
                            width: 20,
                            height: 20,
                          }}
                        />
                      </View>
                      <View>
                        <Text style={{ paddingLeft: 10, color: '#000' }}>
                          {item.max_guest}
                        </Text>
                      </View>
                    </View>

                    <View
                      style={{
                        flexDirection: 'row',
                        width: width * (60 / 375),
                        marginTop: 4,
                        borderRadius: width * (8 / 375),
                        borderWidth: 1,
                        borderColor: color.appTextColor,
                        justifyContent: 'center',
                        alignItems: 'center',
                      }}>
                      <View>
                        <Image
                          resizeMode="contain"
                          source={Images.BedIcons}
                          style={{
                            paddingLeft: 10,
                            width: 20,
                            height: 20,
                          }}
                        />
                      </View>
                      <View>
                        <Text style={{ paddingLeft: 10, color: '#000' }}>
                          {item?.get_property_bedroom?.map(
                            bed => bed.no_of_bedrooms,
                          )}
                        </Text>
                      </View>
                    </View>
                  </View>
                  {item?.get_user_web?.is_super_host == 'Yes' ? (
                    <View
                      style={{
                        backgroundColor: color.appBlueColor,
                        height: 40,
                        width: 40,
                        borderRadius: 20,
                        alignItems: 'center',
                        justifyContent: 'center',
                      }}>
                      <Image
                        resizeMode="contain"
                        source={Images.SeekLogoIcons}
                        style={{
                          width: 20,
                          height: 20,
                          justifyContent: 'center',
                          alignContent: 'center',
                        }}
                      />
                    </View>
                  ) : null}
                </View>
              </TouchableOpacity>
            </View>
            <View
              style={{
                alignSelf: 'flex-end',
                marginHorizontal: 10,
                zIndex: 1000,
                position: 'absolute',
                right: 5,
                marginTop: 10,
              }}>
              <TouchableOpacity onPress={() => addtoFavoriteHandler(item.id)}>
                <Image
                  source={item.is_fav === 1 ? Images.heart : Images.heartIcon}
                  style={{ height: 24, width: 24 }}
                  resizeMode="contain"
                />
              </TouchableOpacity>
            </View>
          </View>
        ) : null
        }
      </View >
    );
  };
  const renderItem6 = ({ item, index }) => {
    // console.log('=====item 3',item.is_fav)
    let arr = []
    arr.push(item.image)
    item.get_property_images.length > 1 &&
      item.get_property_images?.map(img => {
        arr.push(img?.image)
      }
      )
    let title = item.title.split('|')
    // console.log('=--==-=--=arrrrrr',arr);
    return (
      <View

        style={{ marginBottom: 0, marginHorizontal: 10, marginTop: 16 }}
        // activeOpacity={0.5}
        key={item.id}>
        {/* {item.rare == 'Yes' ? ( */}
        <View>
          <View
            style={styles.flatlistHeader}>

            <View>


              <CustomImageFlatList key={index}
                data={arr.length > 1 ? arr :
                  ["https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60",
                    "https://images.unsplash.com/photo-1670436435847-160ac8dd8d31?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxlZGl0b3JpYWwtZmVlZHw0fHx8ZW58MHx8fHw%3D&auto=format&fit=crop&w=400&q=60"]}
              />

            </View>

            <TouchableOpacity
              onPress={async () => {
                props.navigation.navigate('HomeDetails', {
                  propertyid: item.id,
                  price: item.price,
                }),
                  await AsyncStorage.setItem(
                    'Price',
                    JSON.stringify(item.price),
                  );
              }}
              style={{
                paddingHorizontal: width * (10 / 375),
                marginBottom: width * (1 / 375),
                borderColor: '#E9E9E9',
                borderWidth: 1,

                borderBottomLeftRadius: 10,
                borderBottomRightRadius: 10,

                backgroundColor: '#FFFFFF',
                width: width * (332 / 375),

              }}>
              <Text
                numberOfLines={1}
                style={{
                  marginTop: 5,
                  fontSize: 16,
                  fontWeight: '400',
                  color: color.primaryColorBlack,
                }}>
                {item.title}
              </Text>
              <View
                style={{
                  flexDirection: 'row',
                  justifyContent: 'space-between',
                  width: width * (300 / 375),
                }}>
                <View
                  style={{
                    flexDirection: 'row',
                    width: width * (145 / 375),
                    marginTop: 8,
                    justifyContent: 'center',
                    alignItems: 'center',
                    marginHorizontal: 20,
                  }}>
                  <TouchableOpacity style={{}}>
                    <Image
                      resizeMode="contain"
                      source={Images.LocationIcons}
                      style={{
                        // paddingLeft: 10,
                        width: 20,
                        height: 20,
                        alignSelf: 'center',
                      }}
                    />
                  </TouchableOpacity>
                  <View style={{ marginHorizontal: 2, width: width / 2 - 30 }}>
                    <Text
                      numberOfLines={2}
                      style={{ color: '#393939', marginHorizontal: 6 }}>
                      {/* {item?.get_property_address?.map(add => add.address)} */}
                      {title[1]}

                    </Text>
                  </View>
                </View>
                <View
                  style={{
                    flexDirection: 'row',
                    width: width * (70 / 375),
                    marginTop: 10,
                    justifyContent: 'flex-end',
                    alignItems: 'center',
                  }}>
                  <TouchableOpacity>
                    <Image
                      resizeMode="contain"
                      source={Images.StartIcon}
                      style={{
                        paddingLeft: 10,
                        width: 16,
                        height: 16,
                      }}
                    />
                  </TouchableOpacity>
                  <Text style={{ color: color.primaryColorBlack, marginLeft: 5 }}>

                    {item.avg_rating == null ? 0.0 : item.avg_rating}({item.total_rating == null ? 0 : item.total_rating})
                  </Text>
                </View>
              </View>
              <View style={{ flexDirection: 'row' }}>
                <Text
                  style={{
                    marginTop: 4,
                    fontWeight: '500',
                    color: color.primaryColorBlack,
                  }}>
                  {' '}
                  NGN {item.price}
                </Text>
                <Text
                  style={{
                    marginTop: 4,
                    color: '#393939',
                    marginHorizontal: 12,
                  }}>
                  Night
                </Text>
              </View>

              <View
                style={{
                  flexDirection: 'row',
                  justifyContent: 'space-between',
                  marginBottom: 10,
                }}>
                <View
                  style={{
                    flexDirection: 'row',
                    width: width * (130 / 375),
                    height: 40,
                    marginTop: 4,
                    justifyContent: 'space-between',
                  }}>
                  <View
                    style={{
                      flexDirection: 'row',
                      width: width * (60 / 375),
                      marginTop: 4,
                      borderRadius: width * (8 / 375),
                      borderWidth: 1,
                      borderColor: color.appTextColor,
                      justifyContent: 'center',
                      alignItems: 'center',
                    }}>
                    <View>
                      <Image
                        resizeMode="contain"
                        source={Images.GroupIcons}
                        // source={{ uri: 'C:/Users/Admin/Downloads/Bed.svg' }}
                        style={{
                          paddingLeft: 10,
                          width: 20,
                          height: 20,
                        }}
                      />
                    </View>
                    <View>
                      <Text style={{ paddingLeft: 10, color: '#000' }}>
                        {item.max_guest}
                      </Text>
                    </View>
                  </View>

                  <View
                    style={{
                      flexDirection: 'row',
                      width: width * (60 / 375),
                      marginTop: 4,
                      borderRadius: width * (8 / 375),
                      borderWidth: 1,
                      borderColor: color.appTextColor,
                      justifyContent: 'center',
                      alignItems: 'center',
                    }}>
                    <View>
                      <Image
                        resizeMode="contain"
                        source={Images.BedIcons}
                        style={{
                          paddingLeft: 10,
                          width: 20,
                          height: 20,
                        }}
                      />
                    </View>
                    <View>
                      <Text style={{ paddingLeft: 10, color: '#000' }}>
                        {item?.get_property_bedroom?.map(
                          bed => bed.no_of_bedrooms,
                        )}
                      </Text>
                    </View>
                  </View>
                </View>
                {item?.get_user_web?.is_super_host == 'Yes' ? (
                  <View
                    style={{
                      backgroundColor: color.appBlueColor,
                      height: 40,
                      width: 40,
                      borderRadius: 20,
                      alignItems: 'center',
                      justifyContent: 'center',
                    }}>
                    <Image
                      resizeMode="contain"
                      source={Images.SeekLogoIcons}
                      style={{
                        width: 20,
                        height: 20,
                        justifyContent: 'center',
                        alignContent: 'center',
                      }}
                    />
                  </View>
                ) : null}
              </View>
            </TouchableOpacity>
          </View>
          <View
            style={{
              alignSelf: 'flex-end',
              marginHorizontal: 10,
              zIndex: 1000,
              position: 'absolute',
              right: 5,
              marginTop: 10,
            }}>
            <TouchableOpacity onPress={() => addtoFavoriteHandler(item.id)}>
              <Image
                source={item.is_fav === 1 ? Images.heart : Images.heartIcon}
                style={{ height: 24, width: 24 }}
                resizeMode="contain"
              />
            </TouchableOpacity>
          </View>
        </View>
        {/* ) : null
        } */}
      </View >
    );
  };


  const renderData = ({ item, index }) => {
    return (

      <View style={{ marginTop: 5, alignSelf: 'center', width: width / 2 }}>

        <View style={{ flexDirection: 'row' }} >

          <CheckBox
            // tintColor={'#000000'}
            tintColors={{ true: '#F1592A', false: '#C1C1C1' }}
            // onFillColor='#007aaf'
            onCheckColor='#F1592A'
            style={{ height: 20, width: 20, marginBottom: 10, marginLeft: Platform.OS == 'ios' ? 8 : 0, transform: Platform.OS == 'ios' ? [{ scaleX: 1 }, { scaleY: 1 }] : [{ scaleX: 1.2 }, { scaleY: 1.2 }], }}
            disabled={false}
            value={typeBox == item.id ? true : false}
            onValueChange={(newValue) => {
              // checkBoxClick(index)
              setTypeBox(item.id)
              setTypes(item.name)
              // getAllCategoryApi({type:item.name})
            }
            }
          // onValueChange={onValueChange}
          />
          <Text style={{ width: width / 2 - 30, marginBottom: 10, fontSize: 16, fontWeight: '400', color: color.appTextColor, marginLeft: 12 }}>{item.name}</Text>
        </View>
      </View>
    );
  };

  const removeDuplicates = toggleCheckBox => {
    if (toggleCheckBox) {
      return toggleCheckBox.filter(
        (item, index) => toggleCheckBox.indexOf(item) === index,
      );
    }
  };


  //                CATEGORY
  const renderCategoryDate = ({ item, index }) => {
    // console.log('item is ',item.id);
    return (
      <View style={{ marginTop: 5, alignSelf: 'center', width: width / 2 }}>
        <CheckBoxs
          Heading={item.name}
          onValueChange={(val) => {
            // categorytdata(item.id)
            // setToggleCheckBox([...toggleCheckBox, item.id]);
            // categoryListdata(item.id)
            // categoryListdata(item.id, value)
            console.log(val)
            if (val) {
              toggleCheckBox.push(item.id)
            }
            else {
              let possition = toggleCheckBox.indexOf(item.id)
              console.log(possition, "possition")
              toggleCheckBox.splice(possition, 1)
            }
            console.log(toggleCheckBox)
          }}
          unslectCat={unslectCat}
          setunslectCat={setunslectCat}
        />
      </View>
    );
  };

  const renderServicesItem = ({ item, index }) => {
    // console.log('========item is services ', item);
    return (
      <View
        style={{
          marginVertical: 2,
          width: Platform.OS == 'ios' ? '44%' : '44%',
          //width / 2 + 18
        }}>
        <CheckBoxs
          onValueChange={() => setToggleCheckBox1(item.id)}
          Heading={item.name}
        />
      </View>
    );
  };

  const onclickFilterApiHome = async () => {
    //   let catList = removeDuplicates(toggleCheckBox)
    //   ? removeDuplicates(toggleCheckBox)
    //   : [];
    // let cat = catList.toString() ? catList.toString() : '';




    const Localtoken = await AsyncStorage.getItem('token_id');
    setFilterLoader(true);
    try {
      let body = {
        review: starCount,
        type: types == "all" ? "" : types,
        category: toggleCheckBox.toString(),
        bathroom: select,
        services: toggleCheckBox1,
        featured: isFeatured2,
        no_of_bedrooms: selectBedroom,
      }

      await axios({
        method: 'post',
        url: HOME_URL,
        data: body,
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${Localtoken}`,
        },
      }).then(function (response) {
        if (response.data.status == true) {
          // setHomeData([]);
          // setHomeDataNew([]);
          // setInstantBook([]);

          setFilterLoader(false);
          setHomeData(response.data.data.data.featuredProperty);
          setHomeDataNew(response.data.data.data.superHostProperty);
          setInstantBook(response.data.data.data.rareProperty_book_now);
          setLuxuryBooking(response.data.data.data.luxuryProperty);
          setRareBooking(response.data.data.data.rareProperty);
          setSelect('');
          setSelectBedroom('');
          setToggleCheckBox1('');
          setToggleCheckBox([])

          actionSheetRef?.current?.hide();
        }
      });
    } catch (error) {
      console.log('error is ', error);
      setFilterLoader(false);
    }
  };


  const onClearAllFilter = () => {
    // console.log('kamal')
    setunslectCat(false);
    setTypes([]);
    setToggleCheckBox([]);
    setSelect1('');
    setCategoryFilter([]);
    setSelectBedroom('');
    setSelect('');
    setStarCount(0);
    SetClearecategory(false);
    setServiceFilter([]);
    setBathRooms(Bathroom);
    setBedroomsFilter(Bedroom);
    setIsFeatured(false);
    setIsFeatured1(false);
    getAllServicesApi()
    getAllCategoryApi()
    // getData();
  };

  const renderItemOffer = (item, index) => {
    // console.log(item, "itemoffer");
    return (
      <>
        {loaderOffer ? (
          {/* <MatchCardShimmer /> */ }
        ) : (
          <TouchableOpacity
            activeOpacity={0.5}
            onPress={() =>
              props.navigation.navigate('OfferDetail', {
                offerID: item.item.id,
                homeOffer: 'OFFER',
              })
            }
            style={{
              // flex: 1,
              // marginBottom: width * (1 / 375),
              // borderColor: '#E9E9E9',
              // borderWidth: 1,
              // borderRadius: 80,
              marginHorizontal: width * (10 / 375),
            }}>
            <View style={{}}>
              <ImageBackground
                source={{ uri: item.item.image }}
                style={{
                  width: width * (335 / 375),
                  height: width * (185 / 375),
                  resizeMode: 'contain',
                  borderRadius: 20,
                  flex: 1,
                  overflow: 'hidden',
                }}>
                <View
                  style={{
                    backgroundColor: 'rgba(178, 176, 177, 0.49)',
                    flex: 1,
                    justifyContent: 'flex-end',
                  }}>
                  <Text
                    style={{
                      color: '#fff',
                      width: width / 2 - 30,
                      fontSize: 18,
                      marginHorizontal: 14,
                    }}>
                    #{item?.item?.code}
                  </Text>
                  <Text
                    style={{
                      color: '#fff',
                      marginTop: 4,
                      width: width * (160 / 375),
                      marginHorizontal: 14,
                    }}>
                    {item.item.title}
                  </Text>
                  <Text
                    style={{
                      width: width / 2 - 30,
                      marginVertical: 5,
                      color: '#fff',
                      fontSize: 12,
                      marginHorizontal: 14,
                    }}>
                    {item.item.description}
                  </Text>
                </View>
              </ImageBackground>
            </View>
          </TouchableOpacity>
        )}
      </>
    );
  };
  const getpagination = () => {
    // const { entries, activeSlide } = state
    return (
      <Pagination

        dotsLength={offerData?.length}
        activeDotIndex={activeSlide}
        containerStyle={{ marginTop: "-4%", marginBottom: -20 }}
        dotStyle={styles.activepaginationDots}


        inactiveDotStyle={{
          height: 10,
          width: 10,
          borderRadius: 10 / 2,
          borderWidth: 1,
          borderColor: color.lightGray,
          marginLeft: 1,
          backgroundColor: "#00000070"

        }}
        inactiveDotOpacity={1}
        inactiveDotScale={1}
      />
    );
  }
  const onDayPress = day => {
    // console.log('dddd',day.dateString);
    setModalDate(!modalDate);
    {
      Platform.OS == 'ios' ?
        setDateLocalTrue(true) : setDateLocalTrue(false)
    }
    // setDate(day.dateString);
    setDate(moment(day.dateString).format('YYYY-MM-DD'));
  };
  const onDayPressLocal = day1 => {
    // console.log('-------day', day1);
    setModalCheckOut(!modalCheckOut);
    {
      Platform.OS == 'ios' ?
        setDateTrue(true) : setDateTrue(false)
    }
    setDateLocal(moment(day1.dateString).format('YYYY-MM-DD'));

    // setDateLocal(day1.dateString)
  };
  // console.log('-------datelocal', dateLocal);
  const renderCarouselItem = (index) => (
    <View key={index} style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}><Text style={{ fontSize: 24 }}>Item {index + 1}</Text></View>
  );
  return (


    <SafeAreaView style={{ flex: 1 }}>
      {/* <View
  style={{
    backgroundColor: color.appOrangeColor,
    height: Platform.OS === 'ios' ? 20 : StatusBar.currentHeight,
  }}>
  <StatusBar
    translucent
    backgroundColor={[color.appYellowColor,color.appOrangeColor]}
   
  />
</View>  */}


      {/* <ScrollView showsVerticalScrollIndicator={false}> */}
      <LinearGradient
        colors={[color.appYellowColor, color.appOrangeColor]}
        style={{
          flexDirection: 'row',
          height: 110,
          alignItems: 'flex-end',
          justifyContent: 'center',
          width: '100%',
          paddingVertical: 10,
          top: Platform.OS == 'ios' ? -60 : -40,
          paddingHorizontal: 10
        }}>

        <Pressable
          activeOpacity={0.9}
          style={{ width: '80%' }}
          onPress={() => {
            setSearchVisiable(!searchVisiable)
            setProvinceId('')
          }}>
          <View
            style={{
              flexDirection: 'row',
              paddingHorizontal: 5,
              // width: Platform.OS == 'ios' ?270:270,
              height: 40,
              // alignContent: 'center',
              alignItems: 'center',
              justifyContent: 'space-between',
              backgroundColor: color.white,
              borderRadius: 25,
              marginLeft: 10,
            }}>

            <Text style={{ color: '#000', marginLeft: 6, fontSize: 13 }}>Location</Text>
            <View
              style={{ height: 30, width: 0.5, backgroundColor: 'gray' }}></View>

            <Text style={{ color: '#000', marginHorizontal: 5, fontSize: 13 }}>Any Time</Text>
            <View
              style={{ height: 30, width: 0.5, backgroundColor: 'gray' }}></View>

            <Text style={{ color: '#000', marginHorizontal: 0, fontSize: 13 }}>Add Guest</Text>

            <LinearGradient
              colors={[color.appYellowColor, color.appOrangeColor]}


              style={{ backgroundColor: 'orange', height: 30, width: 30, justifyContent: 'center', alignItems: 'center', borderRadius: 20 }}
            >
              <View>
                <Image
                  resizeMode="contain"
                  tintColor={'#fff'}
                  style={{ height: 16, width: 16, }}
                  source={Images.SearchOrangeIcons}
                />
              </View>
            </LinearGradient>
          </View>
        </Pressable>
        <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
          <ReactNativeModal
            isVisible={searchVisiable}

            style={{
              backgroundColor: '#fff',
              borderRadius: 20,
              //flex: 1,
              // justifyContent: 'center',
              // alignItems: 'center',
              marginTop: 40,
            }}


            onDismiss={() => setSearchVisiable(false)}
            onBackdropPress={() => setSearchVisiable(false)}>

            <ScrollView style={{ flex: 1, marginVertical: 20, }}>
              <Pressable onPress={() => setSearchVisiable(false)}>
                <Image
                  source={Images.crossIcon}
                  style={{
                    height: 20,
                    width: 20,
                    alignSelf: 'flex-end',
                    marginHorizontal: 20,
                  }}
                />
              </Pressable>
              <Text style={{ marginHorizontal: 20, fontSize: 16, color: '#000' }}>
                Location
              </Text>
              <Dropdown
                style={{
                  marginHorizontal: 20,
                  height: 50,
                  margin: 10,
                  backgroundColor: color.appTextBackgoundColor,
                  borderRadius: 10,
                  fontSize: 15,
                  paddingHorizontal: 20,
                }}
                selectedTextProps={{ style: { color: '#000' } }}
                itemTextStyle={{ color: '#000' }}
                placeholderStyle={{ color: '#000' }}
                // data={provinceData.country || []}
                data={allProvince || []}
                search={false}
                onFocus={() => setGuestModal(false)}
                maxHeight={300}
                labelField="label"
                valueField="value"
                placeholder="All"
                // searchPlaceholder="5 guests"
                value={allProvince.label}
                onChange={item => {
                  console.log('----f-ff', item.label, item.value);
                  setProvinceId(item.label);
                  provinceId = item?.value;
                  setGuestModal(false);
                  // getCityApi(item.value)
                  getAllCityAreaApi(item?.value)

                }}
              />
              {/* {
                ProvinceId != '' ? */}

              < View >
                <Text style={{ marginHorizontal: 20, fontSize: 16, color: '#000' }}>
                  City/Area
                </Text>
                <Dropdown
                  style={{
                    // height:40,
                    // marginHorizontal:20,
                    paddingLeft: 15,
                    height: 50,
                    margin: 10,
                    backgroundColor: color.appTextBackgoundColor,
                    borderRadius: 10,
                    marginLeft: 20,
                    marginRight: 20,
                    fontSize: 15,
                    paddingRight: 15,
                  }}
                  selectedTextProps={{ style: { color: '#000' } }}
                  itemTextStyle={{ color: '#000' }}
                  placeholderStyle={{ color: '#000' }}

                  data={allCityArea || []}
                  // data={cityData.country || []}
                  onFocus={() => setGuestModal(false)}
                  search={false}
                  maxHeight={300}
                  labelField="label"
                  valueField="value"
                  placeholder="Select City"
                  // searchPlaceholder="5 guests"
                  value={allCityArea.label}
                  onChange={item => {
                    setCity(item.label);
                    cityId = item.value;
                    setGuestModal(false);
                  }}
                />
                <Text style={{ marginHorizontal: 20, fontSize: 16, color: '#000' }}>
                  Any Time
                </Text>
                <View
                  style={{
                    marginTop: 5,
                    flexDirection: 'row',
                    justifyContent: 'space-between',
                    alignItems: 'center',
                    borderRadius: 10,
                    borderWidth: 0,
                    padding: 0,
                    marginHorizontal: 20,
                    borderColor: '#f3f6f9',
                  }}>
                  {/* <TouchableOpacity
                    onPress={() => [
                      setModalDate(!modalDate),
                      setGuestModal(false),
                    ]}> */}
                  <TouchableOpacity
                    onPress={() => [setModalDate(!modalDate), setGuestModal(false)]}
                    style={{
                      borderWidth: 1,
                      borderColor: '#f3f6f9',
                      marginHorizontal: 0,
                      padding: 8,
                      borderRadius: 8,
                      width: '48%',
                      backgroundColor: color.appTextBackgoundColor
                      // height: 60,
                    }}>
                    <Text style={{ fontSize: 16, color: '#000' }}>Check In</Text>
                    {/* <Text>{ProvinceId}</Text> */}
                    <View
                      style={{
                        backgroundColor: color.appTextBackgoundColor,
                        // borderRightWidth: 1,
                        // borderRightColor: '#E9E9E9',
                        justifyContent: 'center',
                        borderBottomLeftRadius: 10,
                        borderBottomColor: '#cdc6c6',
                        // marginHorizontal: 20,
                        marginTop: 5,
                      }}>
                      <ReactNativeModal
                        isVisible={modalDate}
                        // transparent
                        // backgroundColor='rgba(33,33,33,0.1)'
                        onDismiss={() => setModalDate(false)}
                        onBackdropPress={() => setModalDate(false)}
                      // style={{ backgroundColor: 'red', height: 300, width: '100%' }}
                      >
                        <Calendar
                          // minDate={Platform.OS == 'ios' ? new Date() : moment(date).format('YYYY-MM-DD')}
                          // maxDate={moment(new Date()).format('YYYY-MM-DD') == moment(dateLocal).format('YYYY-MM-DD') ? "2030-09-09" : moment(dateLocal).format('YYYY-MM-DD')}

                          current={date == 'Select Date' ? new Date() : moment(date).format('YYYY-MM-DD')}
                          // current={Platform.OS == 'ios' ? new Date() : moment(date).format('YYYY-MM-DD')}

                          minDate={date == 'Select Date' ? new Date() : moment(date).format('YYYY-MM-DD')}
                          // minDate={Platform.OS == 'ios' ? new Date() : moment(date).format('YYYY-MM-DD')}

                          maxDate={dateLocal == 'Select Date' ? "2030-09-09" : moment(dateLocal).format('YYYY-MM-DD')}


                          onDayPress={onDayPress}
                          onDayLongPress={day => {
                            // console.log('selected day', day);
                          }}
                          enableSwipeMonths={true}
                          style={{
                            height: 200,
                            marginHorizontal: 20,
                            marginTop: 100,
                          }}
                        />
                      </ReactNativeModal>

                      {/* {
                            Platform.OS == 'ios' ? <Text style={{ color: color.appTextColor }}>
                              {dateLocalTrue ? moment(date).format('YYYY-MM-DD') : moment(date, "M/D/YYYY, h:mm:ss A").format("YYYY-MM-DD")}
                            </Text>
                              :
                              <Text style={{ color: color.appTextColor }}>
                                {moment(date).format('YYYY-MM-DD')}
                              </Text>
                          } */}
                      {
                        date == "Select Date" ?
                          <Text style={{ color: color.appTextColor }}>
                            {date}
                          </Text>
                          :

                          <Text style={{ color: color.appTextColor }}>
                            {moment(date).format('YYYY-MM-DD')}
                          </Text>



                      }
                    </View>
                  </TouchableOpacity>
                  <TouchableOpacity
                    onPress={() => [
                      day => setDateLocal(day),
                      setModalCheckOut(!modalCheckOut),
                      setGuestModal(false),
                    ]}
                    style={{
                      borderWidth: 1,
                      borderColor: '#f3f6f9',
                      marginHorizontal: 0,
                      padding: 8,
                      borderRadius: 8,
                      width: '48%',
                      backgroundColor: color.appTextBackgoundColor
                      // height: 60,
                    }}>
                    <Text
                      style={{
                        marginHorizontal: 0,
                        color: '#000',
                        fontSize: 16,
                        marginBottom: 5,
                      }}>
                      Check Out
                    </Text>
                    <View
                      style={{
                        backgroundColor: color.appTextBackgoundColor,
                        borderBottomRightRadius: 10,
                        // paddingLeft: 16,
                        justifyContent: 'center',
                        borderBottomColor: '#cdc6c6',

                      }}>
                      <ReactNativeModal
                        isVisible={modalCheckOut}
                        transparent
                      // style={{ backgroundColor: 'red', height: 300, width: '100%' }}
                      >
                        <Calendar
                          // date={dateLocal}

                          // minDate={moment(date).format('YYYY-MM-DD')}
                          minDate={date == 'Select Date' ? new Date() : moment(date).format('YYYY-MM-DD')}

                          // Maximum date that can be selected, dates after maxDate will be grayed out. Default = undefined
                          // maxDate={moment(date).format('YYYY-MM-DD')}
                          onDayPress={onDayPressLocal}
                          style={{
                            marginHorizontal: 20,
                            height: 200,
                            marginTop: 100,
                          }}
                        />
                      </ReactNativeModal>

                      {/* {Platform.OS == 'ios' ? <Text style={{ color: color.appTextColor }}>
                            {/* {moment(dateLocal, "M/D/YYYY, h:mm:ss A").format("DD-MM-YYYY")} */}
                      {/* {dateTrue ? moment(dateLocal).format('YYYY-MM-DD') : moment(dateLocal, "M/D/YYYY, h:mm:ss A").format("YYYY-MM-DD")}
                          </Text> :
                            <Text style={{ color: color.appTextColor }}>
                              {moment(dateLocal).format('YYYY-MM-DD')}
                            </Text>
                          } */}
                      {
                        dateLocal == "Select Date" ?
                          <Text style={{ color: color.appTextColor }}>
                            {dateLocal}
                          </Text>
                          :
                          Platform.OS == 'android' ? <Text style={{ color: color.appTextColor }}>
                            {moment(dateLocal).format('YYYY-MM-DD')}
                          </Text> : <Text style={{ color: color.appTextColor }}>
                            {dateLocalTrue ? moment(dateLocal).format('YYYY-MM-DD') : moment(dateLocal, "DD/MM/YYYY, h:mm:ss A").format("YYYY-MM-DD")}
                          </Text>


                      }
                    </View>
                  </TouchableOpacity>
                </View>
                <View style={{ marginTop: 10 }}>
                  <Text style={{ marginHorizontal: 20, fontSize: 16, color: '#000' }}>
                    Bedrooms
                  </Text>
                  <Dropdown
                    data={BedroomsCount}
                    search={false}
                    onFocus={() => setGuestModal(false)}
                    maxHeight={300}
                    valueField="value"
                    labelField="label"
                    placeholder="0"
                    itemTextStyle={{ color: '#000' }}
                    placeholderStyle={{ color: '#000' }}
                    selectedTextProps={{ style: { color: '#000' } }}
                    value={BedroomsCount.label}
                    onChange={item => {
                      setBedrooms(item.label);
                      setGuestModal(false);
                    }}
                    style={{
                      paddingLeft: 15,
                      height: 50,
                      margin: 10,
                      backgroundColor: color.appTextBackgoundColor,
                      borderRadius: 10,
                      marginLeft: 20,
                      marginRight: 20,
                      fontSize: 15,
                      paddingRight: 15,
                    }}
                  />
                </View>
                <View>
                  <Text style={{ marginHorizontal: 20, fontSize: 16, color: '#000' }}>
                    Who
                  </Text>
                  <TouchableOpacity
                    onPress={() => setGuestModal(!guestModal)}
                    style={{
                      flexDirection: 'row',
                      justifyContent: 'space-between',
                      alignItems: 'center',
                      marginHorizontal: 20,
                      paddingLeft: 15,
                      height: 50,
                      margin: 10,
                      backgroundColor: color.appTextBackgoundColor,
                      borderRadius: 10,
                      marginLeft: 20,
                      marginRight: 20,
                      fontSize: 15,
                      paddingRight: 15,
                    }}>
                    <Text style={{ color: '#000' }}>
                      {count1 + count2 + count3 + count4} guest
                    </Text>
                    <TouchableOpacity
                      style={{
                        // transform: [{ rotate: '95deg' }],
                        marginHorizontal: -6,
                      }}>
                      <Image
                        source={Images.DownArrow}
                        style={{ height: 28, width: 28, tintColor: 'gray' }}
                      />
                    </TouchableOpacity>
                  </TouchableOpacity>
                </View>

                {guestModal == true ? (
                  <View
                    style={{
                      borderRadius: 10,
                      // marginHorizontal: 20,
                      alignSelf: 'center',
                    }}>
                    <View
                      style={{
                        flexDirection: 'row',
                        justifyContent: 'space-between',
                        marginHorizontal: 18,
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
                        <Text
                          style={{ fontSize: 15, fontWeight: '400', color: '#000' }}>
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
                        marginHorizontal: 20,
                        backgroundColor: color.appTextBackgoundColor,
                      }}>
                      <View style={{ marginLeft: 10, marginTop: 10, width: 200 }}>
                        <Text
                          style={{
                            fontSize: 15,
                            fontWeight: '400',
                            color: color.primaryColorBlack,
                          }}>
                          Children
                        </Text>
                        <Text
                          style={{ fontSize: 15, fontWeight: '400', color: '#000' }}>
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
                        marginHorizontal: 20,
                        backgroundColor: color.appTextBackgoundColor,
                      }}>
                      <View style={{ marginLeft: 10, marginTop: 10, width: 200 }}>
                        <Text
                          style={{
                            fontSize: 15,
                            fontWeight: '400',
                            color: color.primaryColorBlack,
                          }}>
                          Infants
                        </Text>
                        <Text
                          style={{ fontSize: 15, fontWeight: '400', color: '#000' }}>
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
                        marginHorizontal: 20,
                        backgroundColor: color.appTextBackgoundColor,
                      }}>
                      <View style={{ marginLeft: 10, marginTop: 10, width: 200 }}>
                        <Text
                          style={{
                            fontSize: 15,
                            fontWeight: '400',
                            color: color.primaryColorBlack,
                          }}>
                          Pets
                        </Text>
                        <Text
                          style={{ fontSize: 15, fontWeight: '400', color: '#000' }}>
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

              </View>
              {/* : null} */}
              {/* </ReactNativeModal> */}
              <TouchableOpacity
                onPress={
                  () =>
                  // homeSearchApiHandlerWithModal()
                  {
                    props.navigation.navigate('Homeview', {
                      PROVINCEID: provinceId,
                      AREAID: cityId,
                      MAX_GUEST: count1 + count2 + count3,
                      CHECKIN: moment(date).format('YYYY-MM-DD'),
                      CHECKOUT: dateLocal,
                      BEDROOMS: bedrooms
                    }),
                      setSearchVisiable(false)
                  }}
                style={{
                  marginTop: 20,
                  backgroundColor: color.appOrangeColor,
                  width: '90%',
                  padding: 10,
                  borderRadius: 10,
                  justifyContent: 'center',
                  height: 50,
                  marginHorizontal: 14,
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
                <View
                  style={{
                    flexDirection: 'row',
                    justifyContent: 'center',
                    alignItems: 'center',
                  }}>
                  <Text
                    style={{
                      textAlign: 'center',
                      fontSize: 15,
                      color: color.appWhiteColor,
                      marginHorizontal: 20,
                    }}>
                    Search
                  </Text>
                  <View>
                    {searchLoader ? (
                      <View style={{ marginHorizontal: 0 }}>
                        <ActivityIndicator size={'small'} color="#fff" />
                      </View>
                    ) : null}
                  </View>
                </View>
              </TouchableOpacity>
            </ScrollView>
          </ReactNativeModal>
        </View>
        <View
          style={{
            flexDirection: 'row',
            marginHorizontal: 0,
            marginBottom: 4,
            justifyContent: 'space-around',
            alignItems: 'center',

            // backgroundColor:'white',
            width: '20%',
            marginLeft: 4
          }}>
          <Pressable
            // style={{ marginLeft: width * (4 / 375) }}
            activeOpacity={0.9}
            onPress={() =>
              props.navigation.navigate('Chat', { fromHome: 'HomeScreen' })
            }>
            <Image
              resizeMode="cover"
              source={Images.Chats}
              style={{ height: 32, width: 32 }}
            />
          </Pressable>
          <Pressable
            activeOpacity={0.9}
            // style={{ marginRight: 0, marginLeft: 4 }}
            onPress={() => actionSheetRef.current?.show()}>
            <Image
              resizeMode="cover"
              source={Images.Filters}
              style={{ height: 32, width: 32, marginLeft: 0 }}
            />
          </Pressable>
        </View>
      </LinearGradient>

      {
        loader ?
          <SliderShimmer />
          : (
            <View style={{ top: Platform.OS == 'ios' ? -60 : -40, }}>
              <ImageBackground style={{
                height: 100, width: '100%',

                marginBottom: 0,
                justifyContent: 'center'
              }} source={{ uri: 'https://shortletrental.com/assets/web/img/slider-banner.png' }}>




                <View
                  style={{
                    // width: ScreenWidth - 20,
                    // justifyContent: 'space-between',
                    // marginHorizontal: 10,
                    // backgroundColor:'yellow',
                    flexDirection: 'row',

                    alignItems: 'center',
                    alignContent: 'center',

                  }}>
                  {/* {
                  data == [] || data == '' ?
                    <View></View> :
                    <View style={{ zIndex: 10000, alignSelf: 'center'}}>
                      <TouchableOpacity
                        onPress={() => {
                          if (selectNo >= 3) {
                            setSelectNo(selectNo - 3);
                            scrollRef.current.scrollToIndex({ index: selectNo })
                          } else {
                            scrollRef.current.scrollToIndex({ index: 0 })
                          }

                          console.log('sele', selectNo);
                        }
                        }
                        style={{ backgroundColor: color.white, height: 30, width: 30, borderRadius: 15, justifyContent: 'center', alignItems: 'center' }}>
                        <Image source={Images.arrIcon} style={{ height: 16, width: 16, resizeMode: 'contain', transform: [{ rotate: '186deg' }], }} />
                      </TouchableOpacity>
                    </View>
                }  */}

                  <View style={{ flex: 1 }}>
                    <FlatList
                      ref={scrollRef}
                      data={data}
                      horizontal
                      pagingEnabled
                      renderItem={renderItem}
                      snapToAlignment='center'
                      onEndReached={() => {
                        if (!isListEnd) {
                          setPageNo(pageNo + 1);
                        }
                      }}
                      keyExtractor={(_, index) => index.toString()}
                      showsHorizontalScrollIndicator={false}
                      ListFooterComponent={
                        bottomLoading && (
                          <View style={{ height: 50, width: '100%', marginTop: 25 }}>
                            <ActivityIndicator size={'small'} color="white" />
                          </View>
                        )
                      }

                    />
                  </View>
                  {/* {
                  data == [] || data == '' ?
                    <View></View> :
                    <View style={{ zIndex: 10000, alignSelf: 'center'}}>
                      <TouchableOpacity
                        onPress={() => {
                          if (selectNo < data.length - 1) {
                            setSelectNo(selectNo + 3);
                          } else {
                            // setSelectNo(0)
                          }
                          scrollRef.current.scrollToIndex({ index: selectNo })
                          console.log('sele', selectNo);
                        }
                        }
                        style={{ backgroundColor: color.white, height: 30, width: 30, borderRadius: 15, justifyContent: 'center', alignItems: 'center' }}>
                        <Image source={Images.ArrowIcons} style={{ height: 16, width: 16, resizeMode: 'contain', transform: [{ rotate: '3deg' }] }} />
                      </TouchableOpacity>
                    </View>
                }  */}
                </View>

              </ImageBackground>
              <ScrollView
                showsVerticalScrollIndicator={false}
                nestedScrollEnabled={true}
                style={{
                  // flex: 1,
                  height: ScreenHeight - 100,
                  // position:'absolute',
                  // top: Platform.OS == 'ios' ? -60 : -40,
                  // marginBottom: Platform.OS == 'ios' ? '-30%' : -40,
                  // flexGrow: 1
                }}>


                <View style={{ marginBottom: -10, marginHorizontal: 10 }}>
                  {homeData == '' || homeData == undefined || homeData == [] ? (
                    <Text></Text>
                  ) : (
                    <View>
                      <View
                        style={{
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                          marginHorizontal: 10,
                          alignItems: 'center',
                          marginTop: 10,
                        }}>
                        <Text style={{ fontSize: 18, color: '#000', fontWeight: '600' }}>
                          Featured Listings
                          {/* Properties */}
                        </Text>
                        <TouchableOpacity
                          onPress={() =>
                            props?.navigation?.navigate('Homeview', { ID: '', featured: 'Yes' })
                          }>
                          <Text style={{ color: '#F1592A' }}>View All</Text>
                        </TouchableOpacity>
                      </View>
                      <FlatList
                        data={homeData}
                        // horizontal
                        renderItem={renderItem1}
                        // nestedScrollEnabled={true}

                        showsHorizontalScrollIndicator={false}
                        // onEndReached={() => {
                        //   if (!isListEnd) {
                        //     setPageNo(pageNo + 1)
                        //   }
                        // }}
                        // onEndReachedThreshold={0.5}
                        keyExtractor={(_, index) => index.toString()}
                        // showsVerticalScrollIndicator={false}
                        // ListFooterComponent={
                        //   bottomLoading && (
                        //     <View style={{ height: 50, width: '100%' }}>
                        //       <ActivityIndicator size={'small'} color="black" />
                        //     </View>
                        //   )
                        // }
                        pagingEnabled={true}
                        snapToAlignment="center"
                      />
                    </View>
                  )}
                </View>
                <View style={{ marginHorizontal: 10 }}>
                  {offerData == '' ? (
                    <Text style={{ fontSize: 20, alignSelf: 'center' }}></Text>

                  ) : (
                    <View style={{}}>
                      <View
                        style={{
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                          marginHorizontal: 10,
                          alignItems: 'center',
                          marginVertical: 10,
                        }}>
                        <Text style={{ fontSize: 18, color: '#000', fontWeight: '600' }}>
                          Special Offers
                        </Text>
                        <TouchableOpacity
                          onPress={
                            () =>
                              props.navigation.navigate('OfferHome', {
                                // homeOffer: 'OFFER',
                              })
                            // props.navigation.navigate('Homeview',{ID:''})
                          }>
                          <Text style={{ color: '#F1592A' }}>View All</Text>
                        </TouchableOpacity>
                      </View>
                      {/* <FlatList
                        // pagingEnabled
                        
                        horizontal
                        data={offerData}
                        showsHorizontalScrollIndicator={false}
                        renderItem={renderItemOffer}
                        keyExtractor={(item, index) => index.toString()}
                        pagingEnabled={true}
                        snapToAlignment="center"
                      // nestedScrollEnabled={true}

                      /> */}
                      {/* <View style={[styles.slider, { flex: 0.3 }]}> */}

                      <Carousel
                        ref={ref}
                        loop={true}
                        autoplay={true}
                        data={offerData || []}
                        renderItem={renderItemOffer}
                        sliderWidth={width}
                        itemWidth={width - 10}

                        activeSlideAlignment="center"
                        onSnapToItem={(index) => setActiveSlide(index)}
                        inactiveSlideScale={1}
                        inactiveSlideOpacity={1}

                        useNativeDriver

                        autoplayDelay={1000}
                        autoplayInterval={1000}

                      />

                      {getpagination()}
                      {/* </View> */}
                    </View>
                  )}
                </View>
                <View style={{ flex: 1 }}>
                  {homeDataNew == '' || homeData == undefined ? (
                    <Text></Text>
                  ) : (
                    // <Text style={{ fontSize: 20, alignSelf: 'center' }}>No Data Found</Text>
                    <View style={{ marginHorizontal: 10 }}>
                      <View
                        style={{
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                          marginHorizontal: 10,
                          alignItems: 'center',
                          marginBottom: 10,
                          marginTop: 10,
                        }}>
                        <Text style={{ fontSize: 18, color: '#000', fontWeight: '600' }}>
                          Superhost- host who provide exceptional services
                          {/* Superhost Properties */}
                        </Text>
                        <TouchableOpacity
                          onPress={() =>
                            props?.navigation?.navigate('Homeview', {
                              SUPERHOST: 'Yes',
                            })
                          }>
                          <Text style={{ color: '#F1592A' }}>View All</Text>
                        </TouchableOpacity>
                      </View>
                      <FlatList
                        // pagingEnabled
                        pagingEnabled={true}
                        snapToAlignment="center"
                        data={homeDataNew}
                        // horizontal
                        // nestedScrollEnabled={true}

                        showsHorizontalScrollIndicator={false}
                        // nestedScrollEnabled
                        renderItem={renderItem2}

                        onEndReachedThreshold={0.5}
                        keyExtractor={(_, index) => index.toString()}
                      // showsVerticalScrollIndicator={false}
                      // ListFooterComponent={
                      //   bottomLoading && (
                      //     <View style={{ height: 50, width: '100%' }}>
                      //       <ActivityIndicator size={'small'} color="black" />
                      //     </View>
                      //   )
                      // }
                      />
                    </View>
                  )}
                </View>

                <View style={{ marginHorizontal: 10 }}>
                  {instantBook == [] || instantBook == '' ? (
                    //  <Text style={{ fontSize: 20, alignSelf: 'center' }}>No Data Found</Text>
                    <View>

                    </View>
                  ) : (
                    <View style={{}}>
                      <View
                        style={{
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                          marginHorizontal: 10,
                          alignItems: 'center',
                          // marginTop: 10,
                        }}>
                        <Text style={{ fontSize: 18, color: '#000', fontWeight: '600' }}>
                          Listings you can book instantly
                          {/* Instant Book */}
                        </Text>
                        <TouchableOpacity
                          onPress={() =>
                            props?.navigation?.navigate('Homeview', {
                              ID: '',
                              Instant: 'Book_now',
                            })
                          }>
                          <Text style={{ color: '#F1592A' }}>View All</Text>
                        </TouchableOpacity>
                      </View>
                      <FlatList
                        pagingEnabled={true}
                        snapToAlignment="center"
                        data={instantBook}
                        // nestedScrollEnabled={true}

                        renderItem={renderItem3}
                        showsHorizontalScrollIndicator={false}
                        keyExtractor={(_, index) => index.toString()}

                      />
                    </View>
                  )}
                </View>

                <View style={{ marginHorizontal: 10, }}>
                  {luxuryBooking == [] || luxuryBooking == '' ? (
                    //  <Text style={{ fontSize: 20, alignSelf: 'center' }}>No Data Found</Text>
                    <Text></Text>
                  ) : (
                    <View style={{}}>
                      <View
                        style={{
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                          marginHorizontal: 10,
                          alignItems: 'center',
                          marginTop: 10,
                        }}>
                        <Text style={{ fontSize: 18, color: '#000', fontWeight: '600' }}>
                          Luxury Listings
                        </Text>
                        <TouchableOpacity
                          onPress={() =>
                            props?.navigation?.navigate('Homeview', {
                              ID: '',
                              LUXURY: 'Yes',
                            })
                          }>
                          <Text style={{ color: '#F1592A' }}>View All</Text>
                        </TouchableOpacity>
                      </View>
                      <FlatList
                        pagingEnabled={true}
                        snapToAlignment="center"
                        data={luxuryBooking}
                        // nestedScrollEnabled={true}

                        renderItem={renderItem4}
                        showsHorizontalScrollIndicator={false}
                        keyExtractor={(_, index) => index.toString()}

                      />
                    </View>
                  )}
                </View>
                <View style={{ marginHorizontal: 10, marginTop: 10 }}>
                  <Text style={{ fontSize: 18, color: '#000', marginHorizontal: 10, marginBottom: 5, fontWeight: '600' }}>
                    Listings By Ratings
                  </Text>
                  <FlatList
                    // pagingEnabled
                    // nestedScrollEnabled={true}

                    pagingEnabled={true}
                    snapToAlignment="center"
                    data={Category}
                    showsHorizontalScrollIndicator={false}
                    renderItem={(item, index) => {
                      return (
                        <TouchableOpacity
                          activeOpacity={0.5}
                          onPress={() =>
                            props?.navigation?.navigate('Homeview', {
                              REVIEW: item?.item?.title,
                            })
                          }
                          style={{ marginHorizontal: 10, marginBottom: 10 }}>
                          <Image
                            source={item?.item?.url}
                            style={{
                              height: 200,
                              width: width * (332 / 375),
                              borderRadius: 10,
                              borderWidth: 0.5,
                            }}
                            resizeMode="stretch"
                          />
                          {/* <Text
                          style={{
                            fontSize: 16,
                            marginTop: -30,
                            color: '#fff',
                            marginLeft: 20,
                          }}>
                          {item?.item?.title}
                        </Text> */}
                        </TouchableOpacity>
                      );
                    }}
                  />
                </View>
                <View style={{ marginHorizontal: 10, }}>
                  {rareBooking == [] || rareBooking == '' ? (
                    //  <Text style={{ fontSize: 20, alignSelf: 'center' }}>No Data Found</Text>
                    <Text></Text>
                  ) : (
                    <View style={{}}>
                      <View
                        style={{
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                          marginHorizontal: 10,
                          alignItems: 'center',
                          marginTop: 10,
                        }}>
                        <Text style={{ fontSize: 18, color: '#000' }}>
                          Rare Listings
                        </Text>
                        <TouchableOpacity
                          onPress={() =>
                            props?.navigation?.navigate('Homeview', {
                              ID: '',
                              RARE: 'Yes',
                            })
                          }>
                          <Text style={{ color: '#F1592A' }}>View All</Text>
                        </TouchableOpacity>
                      </View>
                      <FlatList
                        pagingEnabled={true}
                        snapToAlignment="center"
                        data={rareBooking}
                        // nestedScrollEnabled={true}

                        renderItem={renderItem5}
                        showsHorizontalScrollIndicator={false}
                        keyExtractor={(_, index) => index.toString()}

                      />
                    </View>
                  )}
                </View>
                <View style={{ marginHorizontal: 10 }}>
                  {province == '' ? (
                    <Text style={{ fontSize: 20, alignSelf: 'center' }}></Text>

                  ) : (
                    <View style={{ marginTop: 5 }}>
                      <Text style={{ marginHorizontal: 10, fontWeight: '600', color: color.primaryColorBlack, fontSize: 18, marginBottom: 5 }}>Listings by locations</Text>
                      <FlatList
                        // pagingEnabled
                        // nestedScrollEnabled={true}

                        pagingEnabled={true}
                        snapToAlignment="center"
                        // horizontal
                        data={province}
                        showsHorizontalScrollIndicator={false}
                        renderItem={(item, index) => {

                          return (
                            <>
                              {item?.item?.image ==
                                `${MAIN_URL}/images/no-image.png` ? <View></View> :
                                <TouchableOpacity
                                  onPress={() =>
                                    props?.navigation?.navigate('Homeview', {
                                      PROVINCEID: item?.item?.id,
                                      from: 'Province',
                                    })
                                  }
                                  activeOpacity={0.5}
                                  style={{ marginVertical: 5 }}>

                                  <View style={{ marginHorizontal: 10, marginBottom: 10 }}>
                                    <Image
                                      source={{ uri: item?.item?.image }}
                                      style={{
                                        height: 200,
                                        width: width * (332 / 375),
                                        borderRadius: 15,
                                        borderWidth: 0.5,
                                      }}
                                      resizeMode="cover"
                                    />
                                    <Text
                                      style={{
                                        fontSize: 16,
                                        marginTop: -30,
                                        color: '#fff',
                                        marginLeft: 20,
                                      }}>
                                      {item?.item?.name}
                                    </Text>
                                  </View>

                                </TouchableOpacity>
                              }
                            </>
                          );
                        }}
                        ListFooterComponent={() => {
                          return <View style={{ marginHorizontal: 10 }} />;
                        }}
                        keyExtractor={(item, index) => index.toString()}
                      />
                    </View>
                  )}
                </View>
                <View style={{ marginHorizontal: 10, }}>
                  {moreListing == [] || moreListing == '' ? (
                    //  <Text style={{ fontSize: 20, alignSelf: 'center' }}>No Data Found</Text>
                    <Text></Text>
                  ) : (
                    <View style={{}}>
                      <View
                        style={{
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                          marginHorizontal: 10,
                          alignItems: 'center',
                          marginTop: 10,
                        }}>
                        <Text style={{ fontSize: 18, color: '#000', fontWeight: '600' }}>
                          More Listings
                        </Text>
                        <TouchableOpacity
                          onPress={() =>
                            props?.navigation?.navigate('Homeview', {
                              ID: '',
                              Instant: 'Book_now',
                            })
                          }>
                          <Text style={{ color: '#F1592A' }}>View All</Text>
                        </TouchableOpacity>
                      </View>
                      <FlatList
                        pagingEnabled={true}
                        snapToAlignment="center"
                        data={moreListing}
                        // nestedScrollEnabled={true} 
                        renderItem={renderItem6}
                        showsHorizontalScrollIndicator={false}
                        keyExtractor={(_, index) => index.toString()}
                        ListFooterComponent={
                          (
                            <TouchableOpacity onPress={() => {
                              // if (morePage != 5  &&  morePage != 4 ) {
                              setMorePage(morePage + 1);
                              // }
                              morePageApi(morePage + 1)
                              // }
                            }
                            } style={{ height: 50, width: '50%', marginTop: 20, alignSelf: 'center', borderRadius: 25, flexDirection: 'row', justifyContent: 'center', alignItems: 'center', backgroundColor: color.appOrangeColor, padding: 12 }}>
                              {/* <ActivityIndicator size={'small'} color="white" style={{ marginLeft: 20 }} /> */}
                              <Text style={{ color: color.white, marginLeft: 10, alignSelf: 'center' }}>Load More</Text>
                            </TouchableOpacity>
                          )
                        }

                      />
                    </View>
                  )}
                </View>
                <View style={{ marginBottom: 200 }} />
              </ScrollView>
            </View>
          )
      }
      <Modal
        // animationType="slide"
        transparent={true}
        visible={modalVisible}
        onRequestClose={() => {
          // Alert.alert('Modal has been closed.');
          setModalVisible(!modalVisible);
        }}>
        <View
          style={{
            flex: 0.1,
            justifyContent: 'center',
            alignItems: 'center',
            marginTop: '70%',
            backgroundColor: 'rgba(0, 0, 0,0.6)',
            marginHorizontal: '40%',
          }}>
          <ActivityIndicator size="large" color="#F99428" />
        </View>
      </Modal>

      <ActionSheet
        ref={actionSheetRef}
      //  gestureEnabled={true}
      >
        <View style={{ height: 600 }}>
          <View
            style={{
              borderBottomColor: color.appTextBackgoundColor,
              borderBottomWidth: 1,
              marginTop: 20,
            }}>
            <View
              style={{
                flexDirection: 'row',
                width: width - 30,
                alignSelf: 'center',
                justifyContent: 'space-between',
                bottom: 10,
              }}>
              <TouchableOpacity>
                <Text
                  style={{
                    fontSize: 20,
                    fontWeight: '500',
                    color: color.primaryColorBlack,
                    marginHorizontal: 8,
                  }}>
                  Filter
                </Text>
              </TouchableOpacity>
              <TouchableOpacity
                onPress={() => onClearAllFilter()}
              //  onPress={() => actionSheetRef.current?.hide()}
              >
                <Text
                  style={{
                    color: color.appOrangeColor,
                    fontWeight: '400',
                    fontSize: 16,
                    marginRight: 8,
                  }}>
                  Clear All
                </Text>
              </TouchableOpacity>
            </View>
          </View>
          <ScrollView>
            <View
              style={{
                marginTop: 8,
                marginLeft: 15,
                borderBottomColor: color.appTextBackgoundColor,
                borderBottomWidth: 1,
              }}>
              <Text
                style={{
                  fontSize: 16,
                  fontWeight: '500',
                  color: color.primaryColorBlack,
                  marginHorizontal: 8,
                }}>
                TYPE OF ACCOMMODATION
              </Text>

              <FlatList
                data={typeData || []}
                renderItem={renderData}
                numColumns={2}
                // nestedScrollEnabled={true}

                keyExtractor={(_, index) => index.toString()}
              />
            </View>

            <View
              style={{
                marginTop: 8,
                marginLeft: 15,
                borderBottomColor: color.appTextBackgoundColor,
                borderBottomWidth: 1,
                width: '100%',
              }}>
              <Text
                style={{
                  fontSize: 16,
                  fontWeight: '500',
                  color: color.primaryColorBlack,
                  marginHorizontal: 8,
                }}>
                CATEGORY
              </Text>
              <FlatList
                data={categoryFilter}
                renderItem={renderCategoryDate}
                numColumns={2}
                keyExtractor={(_, index) => index.toString()}
              // nestedScrollEnabled={true}

              />
            </View>
            <View
              style={{
                marginTop: 8,
                marginLeft: 15,
                borderBottomColor: color.appTextBackgoundColor,
                borderBottomWidth: 1,
                // marginBottom: 20,
              }}>
              <Text
                style={{
                  fontSize: 16,
                  fontWeight: '500',
                  color: color.primaryColorBlack,
                  marginHorizontal: 8,
                }}>
                NUMBER OF BEDROOMS
              </Text>
              <View
                style={{
                  flexDirection: 'row',
                  marginTop: 10,
                  marginBottom: 10,
                  marginLeft: 8,
                }}>
                {BedroomsFilter.map((item, index) => (
                  <TouchableOpacity
                    key={index}
                    activeOpacity={0.9}
                    onPress={() => { setSelectBedroom(item.id) }}
                    style={[
                      styles.container,
                      {
                        backgroundColor:
                          selectBedroom == item.id ? color.appOrangeColor : 'white',
                      },
                    ]}>
                    <Text style={{ color: '#C1C1C1' }}>{item.number}</Text>
                  </TouchableOpacity>
                ))}
              </View>
            </View>
            <View
              style={{
                marginTop: 8,
                marginLeft: 15,
                borderBottomColor: color.appTextBackgoundColor,
                borderBottomWidth: 1,
                // marginBottom: 20,
              }}>
              <Text
                style={{
                  fontSize: 16,
                  fontWeight: '500',
                  color: color.primaryColorBlack,
                  marginHorizontal: 8,
                }}>
                NUMBER OF BATHROOMS
              </Text>
              <View
                style={{
                  flexDirection: 'row',
                  marginTop: 10,
                  marginBottom: 10,
                  marginLeft: 8,
                }}>
                {Bathrooms.map((item, index) => (
                  <TouchableOpacity
                    key={index}
                    activeOpacity={0.9}
                    onPress={() => setSelect(item.id)}
                    style={[
                      styles.container,
                      {
                        backgroundColor:
                          select == item.id ? color.appOrangeColor : 'white',
                      },
                    ]}>
                    <Text style={{ color: '#C1C1C1' }}>{item.number}</Text>
                  </TouchableOpacity>
                ))}
              </View>
            </View>
            <View
              style={{
                marginTop: 8,
                marginLeft: 15,
                borderBottomColor: color.appTextBackgoundColor,
                borderBottomWidth: 1,
              }}>
              <Text
                style={{
                  fontSize: 16,
                  fontWeight: '500',
                  color: color.primaryColorBlack,
                  marginHorizontal: 8,
                }}>
                IS FEATURED
              </Text>
              <View
                style={{
                  flexDirection: 'row',
                  justifyContent: 'flex-start',
                  alignItems: 'center',
                  margin: 8,
                }}>
                {isFeatured == true ? (
                  <TouchableOpacity
                    onPress={() => {
                      setIsFeatured(false);
                    }}
                    style={{
                      borderWidth: 1,
                      borderColor: '#f3f6f9',
                      padding: 8,
                      borderRadius: 10,
                      backgroundColor: isFeatured
                        ? color.appOrangeColor
                        : '#fff',
                    }}>
                    <Text
                      style={{
                        fontSize: 14,
                        color: isFeatured ? '#fff' : '#000',
                      }}>
                      Yes
                    </Text>
                  </TouchableOpacity>
                ) : (
                  <TouchableOpacity
                    onPress={() => {
                      [
                        setIsFeatured(true),
                        setIsFeatured1(false),
                        setIsFeatured2('Yes'),
                      ];
                    }}
                    style={{
                      borderWidth: 1,
                      borderColor: '#f3f6f9',
                      padding: 8,
                      borderRadius: 10,
                      backgroundColor: '#fff',
                    }}>
                    <Text style={{ fontSize: 14, color: '#000' }}>Yes</Text>
                  </TouchableOpacity>
                )}

                {isFeatured1 == true ? (
                  <TouchableOpacity
                    onPress={() => {
                      setIsFeatured1(false);
                    }}
                    style={{
                      borderWidth: 1,
                      borderColor: '#f3f6f9',
                      padding: 8,
                      borderRadius: 10,
                      backgroundColor: isFeatured1
                        ? color.appOrangeColor
                        : '#fff',
                      marginLeft: 10,
                    }}>
                    <Text
                      style={{
                        fontSize: 14,
                        color: isFeatured1 ? '#fff' : '#000',
                      }}>
                      No
                    </Text>
                  </TouchableOpacity>
                ) : (
                  <TouchableOpacity
                    onPress={() => {
                      [
                        setIsFeatured1(true),
                        setIsFeatured(false),
                        setIsFeatured2('No'),
                      ];
                    }}
                    style={{
                      borderWidth: 1,
                      borderColor: '#f3f6f9',
                      padding: 8,
                      borderRadius: 10,
                      backgroundColor: '#fff',
                      marginLeft: 10,
                    }}>
                    <Text style={{ fontSize: 14, color: '#000' }}>No</Text>
                  </TouchableOpacity>
                )}
              </View>
            </View>
            <View
              style={{
                marginTop: 5,
                marginLeft: 15,
                borderBottomColor: color.appTextBackgoundColor,
                borderBottomWidth: 1,
              }}>
              <Text
                style={{
                  fontSize: 16,
                  fontWeight: '500',
                  color: color.primaryColorBlack,
                  marginHorizontal: 8,
                  marginBottom: 5,
                }}>
                AMENITY
              </Text>
              <FlatList
                data={serviceFilter}
                renderItem={renderServicesItem}
                numColumns={2}
                keyExtractor={(_, index) => index.toString()}
              // nestedScrollEnabled={true}

              />
            </View>

            <View
              style={{
                marginTop: 4,
                marginLeft: 15,
                borderBottomColor: color.appTextBackgoundColor,
                marginBottom: 0,
              }}>
              <Text
                style={{
                  fontSize: 16,
                  fontWeight: '500',
                  color: color.primaryColorBlack,
                  marginHorizontal: 8,
                  marginBottom: 8,
                }}>
                REVIEW
              </Text>
              <View style={{ marginHorizontal: 0, alignSelf: 'flex-start' }}>
                <StarRating
                  disabled={false}
                  emptyStar={Images.emptyStarImage}
                  fullStar={Images.StartIcon}
                  // halfStar={'ios-star-half'}
                  // iconSet={Images.selectedIcons}
                  emptyStarColor={'#00000080'}
                  // fullStarColor={'red'}
                  maxStars={5}
                  rating={starCount}
                  selectedStar={rating => onStarRatingPress(rating)}
                  starStyle={{ marginBottom: 20, marginHorizontal: 10 }}
                  starSize={30}
                  containerStyle={{}}
                />
              </View>
            </View>
            <TouchableOpacity
              activeOpacity={0.5}
              onPress={() =>
              // onclickFilterApiHome()
              {
                props.navigation.navigate('Homeview', {
                  REVIEW: starCount,
                  TYPE: types == "All" ? "" : types,
                  category: toggleCheckBox.toString(),
                  bathroom: select,
                  services: toggleCheckBox1,
                  featured: isFeatured2,
                  BEDROOMS: selectBedroom,
                  FILTER: 'filter'
                }),
                  actionSheetRef?.current?.hide()
              }
              }
              style={{
                backgroundColor: color.appOrangeColor,
                width: '90%',
                padding: 10,
                borderRadius: 10,
                justifyContent: 'center',
                alignSelf: 'center',
                height: 50,
                marginHorizontal: 20,
                marginTop: 10,
              }}>
              <View
                style={{
                  position: 'absolute',
                  flex: 1,
                }}>
                <Image
                  resizeMode={'contain'}
                  source={Images.whiteDot}
                  style={{
                    paddingLeft: 50,
                    width: 28,
                    height: 28,
                  }}
                />
              </View>

              <Text
                style={{
                  textAlign: 'center',
                  fontSize: 16,
                  color: color.appWhiteColor,
                }}>
                Filter
              </Text>
            </TouchableOpacity>
            {filterLoader ? (
              <View style={{ top: -32, right: -50 }}>
                <ActivityIndicator size={'small'} color="#fff" />
              </View>
            ) : null}
          </ScrollView>
        </View>
      </ActionSheet>
    </SafeAreaView >

  );
};

const styles = StyleSheet.create({
  container: {
    marginRight: 10,
    borderWidth: 1,
    borderRadius: 5,
    width: 30,
    height: 30,
    justifyContent: 'center',
    alignItems: 'center',
    borderColor: '#C1C1C1',
  },
  activepaginationDots: {
    height: 10,
    width: 30,
    borderRadius: 10 / 2,
    backgroundColor: color.appOrangeColor,
    // marginLeft: 10,
  },
  topTags: {
    flexDirection: 'row',
    height: 25,
    alignContent: 'center',
    alignItems: 'center',
    backgroundColor: color.appWhiteColor,
    borderRadius: 8,
    marginTop: 2,
    marginHorizontal: 4,
  },
  centeredView: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 22,
  },
  modalView: {
    margin: 20,
    backgroundColor: 'white',
    borderRadius: 20,
    padding: 35,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 2,
    },
    shadowOpacity: 0.25,
    shadowRadius: 4,
    elevation: 5,
  },
  button: {
    borderRadius: 20,
    padding: 10,
    elevation: 2,
  },
  buttonOpen: {
    backgroundColor: '#F194FF',
  },
  buttonClose: {
    backgroundColor: '#2196F3',
  },
  textStyle: {
    color: 'white',
    fontWeight: 'bold',
    textAlign: 'center',
  },
  modalText: {
    marginBottom: 15,
    textAlign: 'center',
  },
  row: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center'
  },
  flatlistHeader: {
    borderColor: '#E9E9E9',
    borderBottomLeftRadius: 10,
    borderBottomRightRadius: 10,
    width: width * (332 / 375),
    borderTopRightRadius: 15,
    borderTopLeftRadius: 15,
  },
  featureImage: { height: 53, width: 94, marginBottom: -53, zIndex: 1000 },
  headertitle: {
    marginTop: 5,
    fontSize: 16,
    fontWeight: '400',
    color: color.primaryColorBlack,
  }
});

export default Home;
