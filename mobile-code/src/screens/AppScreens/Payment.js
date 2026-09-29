import React, { useCallback, useContext, useEffect, useState } from 'react';
import CheckBox from '@react-native-community/checkbox';
import {
  View,
  Image,
  Text,
  ImageBackground,
  StyleSheet,
  TouchableOpacity,
  _Text,
  ScrollView,
  StatusBar,
  SafeAreaView,
  FlatList,
  TouchableWithoutFeedback,
  InteractionManager,
  Linking,
  BackHandler,
  Platform,
  Alert
} from 'react-native';
import { color, height, width } from '../../styles/colors';
import Images from '../../styles/Images';
import { commonStyles } from './../../styles/style';
import { Colors, colors } from 'react-native/Libraries/NewAppScreen';
import Header from '../../components/Header';
import TextView from '../../components/TextView';
import { Dropdown } from 'react-native-element-dropdown';
import CheckBoxs from '../../components/CheckBox';
import { useFocusEffect, useIsFocused, useNavigation, useRoute } from '@react-navigation/native';
import { Calendar } from 'react-native-calendars';
import { Context as BookingContext } from '../../context/BookingContext';
import { Context as AuthContext } from '../../context/AuthContext';
import { Context as HomeContext } from '../../context/HomeContext';
import Modal from 'react-native-modal';
import { Toast } from 'react-native-toast-message/lib/src/Toast';
import moment from 'moment';
import CheckBoxFilter from '../../components/CheckBoxFilter';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { BookingCheckout, BookingVerify, Booking_Reserve, GetCartBooking, MAIN_URL, ReserveCheckout, User_Profile, checkOutFromReserve, getCartReserve } from '../../network/Webconstant';
import { ActivityIndicator } from 'react-native';
import axios from 'axios';
import { Paystack } from 'react-native-paystack-webview';
import { TextInput } from 'react-native';
import CountryPicker from 'react-native-country-picker-modal'



let newPrice = "";
const agency = [
  { label: 'Yes', value: 'Yes' },
  { label: 'No', value: 'No' },
]
// let countryId = ''
const Payment = props => {
  const navigation = useNavigation();
  const route = useRoute();
  const { max_Gest,orderID } = route?.params
  // const { price } = props?.route?.params
  // const { convenceFee } = route?.params?.convenceFee
  const [convenceFee, setConvenveFee] = useState(route?.params?.convenceFee);
  const [discount, setDiscount] = useState("");
  const [serviceHieght, setServiceHieght] = useState(170)
  const [countryId,setCountryId] = useState('')

  const [bookingsData, setBookingsData] = useState({})
  const [bookingId, setBookingId] = useState('')
  const [loaderBooking, setLoaderBooking] = useState(true)


  const [value, setValue] = useState(null);
  const [toggleCheckBox1, setToggleCheckBox1] = useState(false);
  const [toggleCheckBox2, setToggleCheckBox2] = useState(false);
  const [date, setDate] = useState(new Date());
  const [dateLocal, setDateLocal] = useState(moment(new Date()).clone().add(1, "days").format('YYYY-MM-DD'));

  const [dateTrue, setDateTrue] = useState(false)
  const [dateLocalTrue, setDateLocalTrue] = useState(false)
  const [modalVisible, setModalVisible] = useState(false);
  const [modal, setModal] = useState(false);
  const [guestModal, setGuestModal] = useState(false);
  const [addAmount, setAddAmount] = useState(null);
  const [amount, setAmount] = useState(false);
  const [addPersonalModal, setAddPersonalModal] = useState(true);
  const [addGuestModal, setAddGuestModal] = useState(true);
  const [securityAmount, setSecurityAmount] = useState(null);
  const [redeemCheck, setRedeemCheck] = useState(false);
  const [extraRedeem, setExtraRedeem] = useState(0);
  const [currentDate, setCurrentDate] = useState('');
  const [unslectCat, setunslectCat] = useState(false)
  const [bookType, setBookType] = useState("")
  const [agreeTerms, setAgreeTerms] = useState(false)
  const [offerTerms, setOfferTerms] = useState(false)

  const [extraAmount, setExtraAmount] = useState(0);
  const [count1, setCount1] = useState(0);
  const [count2, setCount2] = useState(0);
  const [count3, setCount3] = useState(0);
  const [count4, setCount4] = useState(0);
  const isFocused = useIsFocused();
  const [price, setPrice] = useState(0)
  const [loader, setLoader] = useState(false)
  const [selectedOptions, setSelectedOptions] = useState([])
  const [toggleCheckBox, setToggleCheckBox] = useState(true);
  const [bankMode, setBankMode] = useState(false);
  const [pay, setPay] = useState(false)
  //////add personal data
  const [fullName, setFullName] = useState('');
  const [lastName, setLastName] = useState('')
  const [Address, SetAddress] = useState('');
  const [City, SetCity] = useState('');
  const [PostalCode, SetPostalCode] = useState('');
  // const [Country, SetCountry] = useState('');
  const [PhoneNumber, SetPhoneNumber] = useState('');
  const [Email, SetEmail] = useState('');
  const [Comments, SetComments] = useState('');
  const [AreYouAgency, SetAreYouAgency] = useState('');
  const [toggleCheckMain, setToggleCheckBoxMain] = useState(true)
  const [toggleCheckGuest, setToggleCheckGuest] = useState(false)
  // const [value, setValue] = useState(null);
  const [value1, setValue1] = useState(null);
  const [token, setToken] = useState('')
  const [placeholder, setPlaceHolder] = useState('Country')
  const [country, setCountry] = useState('234')
  const [countryCode, setCountryCode] = useState('NG')
  const [visible, setVisible] = useState(false)
  const [province, setProvince] = useState('')
const [reserveBookingID,setReserveBookingID] = useState('')

  const [guestFirstName, setGuestFirstName] = useState('');
  const [guestLastName, setGuestLastName] = useState('')
  const [guestCity, setGuestCity] = useState('');
  const [guestPostalCode, setGuestPostalCode] = useState('');
  const [guestCountry, setGuestCountry] = useState('');
  const [guestPhoneNumber, setGuestPhoneNumber] = useState('');
  const [guestEmail, setGuestEmail] = useState('');
  const [guestProvince, setGuestProvince] = useState('');
  const [guestCountryModal, setGuestCountryModal] = useState('234')
  const [guestCountryCode, setGuestCountryCode] = useState('NG')
  const [guestVisible, setGuestVisible] = useState(false)
  const [guestAddress, setGuestAddress] = useState('')
  const [guestValue, setGuestValue] = useState(null)
  // const [addData, setAddData] = useState([])
  const [guestPlaceholder, setGuestPlaceHolder] = useState('Country')
  // console.log('------------------------------rrrrrrrrrrrr-------------', guestCountryModal, guestCountryCode, toggleCheckMain, toggleCheckGuest);

  useEffect(() => {
    setPay(false)
    if (props.route.params.hotelDetail == 'hotelDetail') {
      setCount1(0)
      setCount2(0)
      setCount3(0)
      setCount4(0)
      setunslectCat(true)
      setExtraAmount(0)
      // calcultateExtraAmount(0)
      setAgreeTerms(false)

    }

    console.log('==============r÷oute.params.points', orderID)
  }, [props])

  useFocusEffect(
    React.useCallback(() => {
      getUserProfile()
      onHandlerBooking()
    }, [props]))

  const onSelect = (country) => {
    setCountryCode(country.cca2)
    setCountry(country.callingCode[0])
  }
  

  const getUserProfile = async () => {

    // setLoader(true);
    const Localtoken = await AsyncStorage.getItem('token_id');
    // myToken = myToken ? Localtoken : null
    try {
      await axios({
        method: 'get',
        url: User_Profile,
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${Localtoken}`,
        },
      })
        .then(function (res) {
          console.log('---res acount', res.data.data)
          if (res.data.status) {
            // setLoader(false);
            // setProfileUserData(res.data.data);
            setFullName(res?.data?.data?.name)
            setLastName(res?.data?.data?.surname)
            SetEmail(res?.data?.data?.email)
            SetPhoneNumber(res?.data?.data?.mobile)
            // setCountry(res?.data?.data?.country_code)
            SetAddress(res?.data?.data?.address)
            setCountryId(Number(res?.data?.data?.country_id))
            setProvince(res?.data?.data?.province_name)
            SetCity(res?.data?.data?.city_name)
            SetPostalCode(res?.data?.data?.postal_code)
            // setEditHandle(true)
          } else {
            // setLoader(false);
      console.log('something went else wrong', error);

          }
        })

    } catch (error) {
      console.log('something went wrong', error);
      // setLoader(false);
    }
  };
  const onHandlerBooking = async () => {
   
   
    const Localtoken = await AsyncStorage.getItem('token_id');
    try {
      setLoaderBooking(true)
      axios({
        method: "get",
        url: getCartReserve+'?reserve_id='+orderID,
        // data:{reserve_id:orderID},
        headers: { Accept: 'application/json', Authorization: `Bearer ${Localtoken}` }
      })
        .then((res) => {
          console.log('resssssssssssssaadddokkkkk', res.data)
        
          if (res.data.status == true) {
            Toast.show({
              type: "success",
              text1: res.data.message
            })
            setBookingsData(res.data.data)
            setValue1(res.data.data.is_agency)
            setProvince(res?.data?.data?.personal_province)
            SetCity(res?.data?.data?.personal_city)
            setCountryId(Number(res?.data?.data?.personal_country_id))

            setLoaderBooking(false)
          } else {
            Toast.show({
              type: "error",
              text2: res.data.message
            })
            setLoaderBooking(false)

          }
        })
    } catch (error) {
      console.log('eerrr', error);
      setLoaderBooking(false)

    }
    // finally{
    //   setLoaderBooking(false)
    // }

  };
  // useEffect(() => {

  //   setunslectCat(true)
  // }, [props])
  

  const handler = () => {
    navigation.navigate('Notification')
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
  const getPrice = async () => {
    let PRICESTORAGE = await AsyncStorage.getItem('Price')
    setPrice(PRICESTORAGE)
  }
  
  const {
    getAllServicesApi,
    state: { homeDetails, details, homeData, services },
  } = useContext(HomeContext);
  const {
    mobileBooking,
    updateBookingData,
    getAllCountryApi,
    state: { bookingData, applyCoupon },
  } = useContext(BookingContext);


  let dayDifference = Math.floor(
    (new Date(moment(dateLocal).format('YYYY-MM-DD')).getTime() - new Date(moment(date).format('YYYY-MM-DD')).getTime()) /
    (1000 * 60 * 60 * 24),
  );
  const activePropertyData = homeData.filter(element => {
    return element.id === id;
  });

  useEffect(() => {
    getAllCountryApi()
  }, [props])


  useEffect(() => {
    setExtraAmount(0)


    var date = new Date().getDate();
    var month = new Date().getMonth() + 1;
    var year = new Date().getFullYear();
    // console.log("current date", year + "-" + month + '-' + date);
    setCurrentDate(year + "-" + month + '-' + date)
    getAllServicesApi();
    // getPrice()
  }, []);

  useFocusEffect(

    React.useCallback(() => {
      // setConvenveFee(route?.params?.convenceFee)
      setDiscount(route?.params?.myId)
      setBookType(route?.params?.bookType)
      getPrice()


      if (route?.params?.myId == null) {
        setDiscount()
      } else {
        setDiscount(route?.params?.myId)
      }
      // (price * dayDifference / route?.params?.myId * 1)
      (route.params.price == '' ? price : route.params.price * dayDifference / route?.params?.myId * 1)

      // setLoader(true)
      // const task = InteractionManager.runAfterInteractions(() => {
      //   // onHandleHomeDetailsApi()
      // });
      // return () => task.cancel();
      // }, [price])
    }, [route.params.price == '' ? price : route.params.price])
  );
  let totalGuest = count1 + count2 + count3 + count4
  useEffect(() => {
    if (max_Gest < totalGuest) {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: `Max number of guest is ${max_Gest}`
      });
      setGuestModal(!guestModal);
    }
  }, [count1, count2, count3, count4])

  

  const onInputChange = (value) => {

    console.log('Input value: ', value);
    const re = /^[a-zA-Z\s]*$/;
    if (value === "" || re.test(value)) {
      setFullName(value);
    }
  }
  const onInputChangeLastName = (value) => {

    console.log('Input value: ', value);
    const re = /^[a-zA-Z\s]*$/;
    if (value === "" || re.test(value)) {
      setLastName(value);
    }
  }
  const onInputChangeCity = (value) => {

    console.log('Input value: ', value);
    const re = /^[a-zA-Z\s]*$/;
    if (value === "" || re.test(value)) {
      SetCity(value);
    }
  }
  const onInputChangeGuestFirstName = (value) => {

    console.log('Input value: ', value);
    const re = /^[a-zA-Z\s]*$/;
    if (value === "" || re.test(value)) {
      setGuestFirstName(value);
    }
  }
  const onInputChangeGuestLastName = (value) => {

    console.log('Input value: ', value);
    const re = /^[a-zA-Z\s]*$/;
    if (value === "" || re.test(value)) {
      setGuestLastName(value);
    }
  }

  const PaymentSuccessApi = async (response) => {
console.log('=--=-=reseve',reserveBookingID);
    const Localtoken = await AsyncStorage.getItem('token_id');
    console.log('data successs api', response)
    try {
      axios({
        method: "post",
        url: BookingVerify,
        data: { reference: response.data.transactionRef.reference, booking_id: reserveBookingID},//bookingsData?.booking_id },
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${Localtoken}`,
        }
      }).then((res) => {
        console.log('res-=-=-=-=-=-=', res.data)
        if (res.data.status) {
          props.navigation.navigate('OrderScreen', { data: response })

          Toast.show({ type: "success", text1: res.data.message })
        } else {

        }
      })
    } catch (error) {
      console.log(error, 'error suuccesss api');
    }
  }
 

  const mobileBookingApiReserve = async () => {

   


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
    }
    else {
      let data = {
        first_name: fullName,
        last_name: lastName,
        address: Address,
        city: City,
        phone_number: PhoneNumber,
        email: Email,
        book_for: toggleCheckMain && 'self',
        is_agency: value1,
        payment_method: bankMode && "card" ,
        policy_read: agreeTerms,
        cart_id: bookingsData?.id,
        per_night_price: bookingsData?.per_night_price,
        // total_booking_amount: Number(bookingsData?.total_booking_amount) + Number(route?.params?.myId) == null ? (route.params.price == '' ? Number(price) : Number(route.params.price) * Number(dayDifference)) + (Number(convenceFee) * 1) + (Number(extraAmount) * 1) :
        //   (route.params.price == '' ? Number(price) : Number(route.params.price) * Number(dayDifference)) + (Number(convenceFee) * 1) + (Number(extraAmount) * 1) - (route.params.price == '' ? Number(price) : Number(route.params.price) * Number(dayDifference) / Number(route?.params?.myId)),
        postal_code: PostalCode,
        total_booking_amount:bookingsData?.total_booking_amount,
        booking_from:'Mobile'
      }

      // const Localtoken = await AsyncStorage.getItem('token_id');
      // let data = {
      //   property_id: route.params.propertyid,//route?.params?.propertyid,
      //   // start_date: route.params.Date,
      //   // end_date: route.params.DateLocal,
      //   start_date: moment(date).format('YYYY-MM-DD'),// moment(route.params.Date).format('YYYY-MM-DD'),
      //   end_date: moment(dateLocal).format('YYYY-MM-DD'),//moment(route.params.DateLocal).format('YYYY-MM-DD'),
      //   total_booking_amount:
      //     redeemCheck ? route?.params?.myId == null ? ((route.params.price == '' ? price : route.params.price * dayDifference) + (convenceFee * 1) + (extraAmount * 1)) - (route?.params?.points_amount == undefined ? 0 : route?.params?.points_amount) :
      //       ((route.params.price == '' ? price : route.params.price * dayDifference) + (convenceFee * 1) + (extraAmount * 1) - (route.params.price == '' ? price : route.params.price * dayDifference / route?.params?.myId)) - (route?.params?.points_amount == undefined ? 0 : route?.params?.points_amount) :

      //       route?.params?.myId == null ? (route.params.price == '' ? price : route.params.price * dayDifference) + (convenceFee * 1) + (extraAmount * 1) :
      //         (route.params.price == '' ? price : route.params.price * dayDifference) + (convenceFee * 1) + (extraAmount * 1) - (route.params.price == '' ? price : route.params.price * dayDifference / route?.params?.myId)

      //   ,


      //   // route?.params?.myId == null ? (route.params.price == '' ? price : route.params.price * dayDifference) + (convenceFee * 1) + (extraAmount * 1) :
      //   //   (route.params.price == '' ? price : route.params.price * dayDifference) + (convenceFee * 1) + (extraAmount * 1) - (route.params.price == '' ? price : route.params.price * dayDifference / route?.params?.myId),

      //   // total_booking_amount: route?.params?.myId == null ? (price * dayDifference) + (route.params.convenceFee * 1) + (extraAmount * 1) :
      //   //   (price * dayDifference) + (route.params.convenceFee * 1) + (extraAmount * 1) - (price * dayDifference / route?.params?.myId),
      //   adultCount: count1,//route.params.Counter1,
      //   childCount: count2,//route.params.Counter2,
      //   infantCount: count3, //route.params.Counter3,
      //   petCount: count4, // route.params.Counter4,
      //   total_days: dayDifference, // route.params.Day,
      //   discount_code: route.params.CODE,
      //   // discount_code:applyCoupon.code,
      //   discount_id: route.params.ID,
      //   discount_amount: route?.params?.myId == null ? "0" : (route.params.price == '' ? price : route.params.price * dayDifference / route?.params?.myId * 1)
      //   // discount_amount: route?.params?.myId == null ? "0" : (price * dayDifference / route?.params?.myId * 1)


      //   ,
      //   is_agency: bookingData.personalData.value1,
      //   payment_method: 'credit_card',
      //   book_for: bookingData.personalData.book_for,
      //   policy_read: 'Yes',
      //   first_name: bookingData.personalData.fullName,
      //   last_name: 'vinod',
      //   address: bookingData.personalData.Address,
      //   // country_id:bookingData.personalData.value,
      //   city: bookingData.personalData.City,
      //   postal_code: bookingData.personalData.PostalCode,
      //   phone_number: bookingData.personalData.PhoneNumber,
      //   email: bookingData.personalData.Email,
      //   comment: bookingData.personalData.Comments,
      //   guest_first_name: bookingData.guestData.fullName,
      //   guest_last_name: bookingData.guestData.lastName,
      //   guest_address: bookingData.guestData.address,
      //   guest_city: bookingData.guestData.city,
      //   guest_zipcode: bookingData.guestData.postalCode,
      //   guest_country_id: bookingData.guestData.value,
      //   guest_phone_number: bookingData.guestData.phoneNumber,
      //   guest_email: bookingData.guestData.email,
      //   // card_holder_name: holderName,
      //   // card_number: cardNumber,
      //   // month: month,
      //   // year: year,
      //   // cvv: cvv,
      //   redeem_royalty_points: 'No',
      //   loyalty_points: redeemCheck ? route.params.loyalty_points : '',
      //   loyalty_amount: redeemCheck ? route.params.loyalty_amount : '',
      //   booking_reserve: 'reserve'
      //   // selected_options:[{"id":"3","name":"Photoshoot","value":"100000"},{"id":"4","name":"Early Check in","value":"5000"},{"id":"7","name":"Game night","value":"10000"}]
      // };
      // console.log('=====dddd',data)
      let headers = {
        Accept: 'application/json',
        Authorization: `Bearer ${Localtoken}`,
      };
      setLoader(true)
      try {
        axios({
          method: 'post',
          url:checkOutFromReserve,
          data,
          headers
        }).then(
          function (response) {
            if (response.data.status) {
              console.log('----------ressssssssssssssss=--=-=-=-=-=', response.data.data.booking_id)
              Toast.show({
                type: 'success',
                text1: 'Shortlet',
                text2: response.data.message,
              });
              setReserveBookingID(response.data.data.booking_id)
              setLoader(false)
              setPay(true)
              // props.navigation.navigate('OrderScreen', { data: response.data.data })
             
            } else {
              Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: response.data.message,
              });
              setLoader(false)
            }
          }
        )
      } catch (error) {
        console.log("BookingContext:", error)
        setLoader(false)
      }
    }
  }
  
  const calcultateExtraAmount = amount => {
    setExtraAmount(prevState => prevState + amount);
  };
  const redeemTotal = price => {
    setExtraRedeem(prevState => prevState + price);
  };
  const renderServicesItem = ({ item, index }) => {
    // console.log('=====item.id', item)
    return (
      <>

        <View
          onPress={() => setExtraAmount(item.price)}
          style={{ flexDirection: 'row', width: width / 2, marginHorizontal: 14, marginVertical: Platform.OS == 'ios' ? 4 : 0, alignItems: 'center', }}>
          <CheckBoxFilter
            Heading={item.name}
            onValueChange={newValue =>
              calcultateExtraAmount(newValue ? item.price : -item.price)
            }
            unslectCat={unslectCat}
            setunslectCat={setunslectCat}
          />
          <Text style={{ marginHorizontal: 10, color: '#000' }}>(Price {item.price})</Text>
        </View>


      </>
    );
  };

  // console.log('============notification', unslectCat, setunslectCat);
  return (
    <>
      {
        loaderBooking ? <View style={{ backgroundColor: "#00000080", flex: 1, justifyContent: "center", alignItems: "center" }}>
          <ActivityIndicator size={'large'} color={color.appOrangeColor} />
        </View>
          :
          <View style={{ backgroundColor: color.appWhiteColor, flex: 1 }}>
            <Header
              props={props}
              Heading={'Booking Reserve Details'}
              onPress={() => navigation.navigate('Notification')}
            />
            <ScrollView>
              <Text
                style={{
                  marginTop: 10,
                  marginHorizontal: 20,
                  color: color.primaryColorBlack,
                  fontSize: 20,
                  fontWeight: '600',
                }}>
                Request to book
              </Text>
              <View style={{ borderWidth: 1, borderColor: color.inputBoxBorderGray, paddingVertical: 20, borderRadius: 12, marginHorizontal: 20, marginVertical: 10 }}>
                <Image source={{ uri: bookingsData?.get_property?.image }} style={{ height: width - 140, width: width - 80, borderRadius: 12, alignSelf: "center" }} />
                <Text
                  numberOfLines={1} style={{
                    marginTop: 10,
                    marginHorizontal: 20,
                    color: color.primaryColorBlack,
                    fontSize: 16,
                    fontWeight: '500',
                  }}>
                  {bookingsData?.get_property?.title}
                </Text>
                <Text
                  style={{
                    marginTop: 10,
                    marginHorizontal: 20,
                    color: color.primaryColorBlack,
                    fontSize: 14,
                    fontWeight: '400',
                  }}>
                  {/* {bookingsData?.get_property?.get_property_address[0]?.address} */}
                  {bookingsData?.get_property?.get_property_address[0]?.get_property_area?.name +"," }
                  {bookingsData?.get_property?.get_property_address[0]?.get_property_city?.name +"," }
                  {bookingsData?.get_property?.get_property_address[0]?.get_property_province?.name +"," }
                  {bookingsData?.get_property?.get_property_address[0]?.get_property_country?.name  }
                </Text>
                <View style={{ flexDirection: "row", marginHorizontal: 20, marginVertical: 5, alignItems: 'center' }}>
                  <Image source={Images.StartIcon} style={{ height: 16, width: 16, }} />
                  <Text style={{ marginLeft: 8, color: color.primaryColorBlack }}>{bookingsData?.get_property?.avg_rating == null ? 0 : bookingsData?.get_property?.avg_rating}{bookingsData?.get_property?.total_rating == null ? 0 : bookingsData?.get_property?.total_rating} Reviews</Text>
                </View>
                <View style={{ marginHorizontal: 20, flexDirection: 'row', alignItems: "center" }}>
                  <View
                    style={{
                      backgroundColor: color.appBlueColor,
                      height: 40,
                      width: 40,
                      borderRadius: 20,
                      alignItems: 'center',
                      justifyContent: 'center',
                      // marginHorizontal:20
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
                  <Text style={{ color: color.primaryColorBlack, marginLeft: 10, fontWeight: '500' }}>Superhost</Text>
                </View>

              </View>

              <View>
                <Text
                  style={{
                    marginTop: 10,
                    marginHorizontal: 20,
                    color: color.primaryColorBlack,
                    fontSize: 20,
                    fontWeight: '600',
                  }}>
                  Additonal notes
                </Text>
                <Text style={{ marginHorizontal: 20 }}><Text style={{ color: color.primaryColorBlack, fontWeight: "500" }}>Check-in schedule </Text> : from {bookingsData?.get_property?.check_in_from_time} to {bookingsData?.get_property?.check_in_to_time} every day</Text>
                <Text style={{ marginHorizontal: 20 }}><Text style={{ color: color.primaryColorBlack, fontWeight: '500' }}>Check-out schedule </Text> : Before {bookingsData?.get_property?.check_out_time}</Text>
              </View>
              <View
                style={{
                  borderBottomWidth: 1,
                  marginVertical: 10,
                  borderBottomColor: '#E9E9E9',

                }}
              />
              <ScrollView contentContainerStyle={{ flexGrow: 1, marginTop: 10 }}>
                <Text
                  style={{
                    marginTop: 10,
                    marginHorizontal: 20,
                    color: color.primaryColorBlack,
                    fontSize: 20,
                    fontWeight: '600',
                  }}>
                  Personal Data
                </Text>
                <TextView
                  placeholder={'First Name'}
                  value={fullName}
                  onChangeText={onInputChange}
                />
                <TextView
                  placeholder={'Last Name'}
                  value={lastName}
                  onChangeText={onInputChangeLastName}
                />
                <View style={{ flexDirection: 'row', justifyContent: 'flex-start', alignItems: 'center', marginHorizontal: 20, }}>


                  <View style={{ flexDirection: 'row', alignItems: 'center', justifyContent: 'flex-start', marginRight: 0, width: '35%', }}>
                    <View style={{
                      height: 50, backgroundColor: color.appTextBackgoundColor, flexDirection: 'row',
                      alignItems: 'center',
                      justifyContent: "flex-start",
                      borderRadius: 8,
                      marginTop: 10,
                      padding: 10
                    }}>
                      <CountryPicker
                        {...{
                          onSelect,
                        }}
                        visible={visible}
                        countryCode={countryCode}
                        withFilter={true}
                      // containerButtonStyle={{backgroundColor:'#fff'}}

                      />

                      <Text style={{ color: '#000' }}>+{country}</Text>
                    </View>
                  </View>
                  <TextInput
                    placeholder={'Phone Number'}
                    value={PhoneNumber}
                    onChangeText={(e) => SetPhoneNumber(e.replace(/[^0-9]/g, ''))}
                    keyboardType={'numeric'}
                    maxLength={12}
                    style={{
                      height: 50,
                      width: '65%',
                      // margin: 10,
                      marginTop: 10,
                      backgroundColor: color.appTextBackgoundColor,
                      borderRadius: 10,
                      paddingHorizontal: 14,
                      fontSize: 15, color: '#000'
                    }} placeholderTextColor={color.appTextColor}
                  />



                </View>
                <View style={{ marginTop: 10 }}>
                  <TextInput
                    placeholder={'E-Mail'}
                    value={Email}
                    onChangeText={(e) => SetEmail(e)}
                    style={{
                      paddingLeft: 15,
                      height: 50,
                      margin: 10,
                      backgroundColor: color.appTextBackgoundColor,
                      borderRadius: 10,
                      marginLeft: 20,
                      marginRight: 20,
                      fontSize: 15,
                      color: '#000',

                    }}
                    placeholderTextColor={color.appTextColor}
                    keyboardType='email-address'

                  />
                </View>
                <TextView
                  placeholder={'Address'}
                  value={Address}
                  onChangeText={(e) => SetAddress(e)}
                />
                <Dropdown
                  style={{
                    paddingLeft: 15,
                    height: 50,
                    margin: 10,
                    backgroundColor: color.appTextBackgoundColor,
                    borderRadius: 10,
                    marginLeft: 20,
                    marginRight: 20,
                    fontSize: 15,
                    paddingRight: 12
                  }}
                  placeholderStyle={{ color: color.appTextColor }}
                  itemTextStyle={{ color: '#000' }}
                  selectedTextProps={{ style: { color: '#000' } }}
                  data={bookingData.country || []}
                  search={true}
                  maxHeight={300}
                  labelField="label"
                  valueField="value"
                  placeholder="Country"
                  searchPlaceholder="Search Country"
                  value={countryId}
                  // value={bookingData.label}

                  onChange={item => {
                    console.log('item,',item);
                    setCountryId(item.value)
                    setValue(item.label);
                    setPlaceHolder(item.label)
                  }}
                />
                <TextView
                  placeholder={'Province'}
                  value={province}
                  onChangeText={(e) => setProvince(e)}
                // isNumeric={true}
                />
                <TextView
                  placeholder={'City'}
                  value={City}
                  onChangeText={onInputChangeCity}
                />
                <TextView
                  // ref='mobileNo'
                  placeholder={'Postal Code'}
                  value={PostalCode}
                  onChangeText={(text) => SetPostalCode(text.replace(/[^0-9]/g, ''))}
                  isNumeric={true}
                  maxLength={6}

                />





                <TextInput
                  placeholder={'Comments'}
                  value={Comments}
                  maxLength={100}
                  onChangeText={(e) => SetComments(e)}
                  style={{
                    paddingHorizontal: 15,
                    height: 50,
                    margin: 10,
                    backgroundColor: color.appTextBackgoundColor,
                    borderRadius: 10,
                    marginLeft: 20,
                    marginRight: 20,
                    fontSize: 15,
                    color: '#000'
                  }}
                />
                {/* <View style={{ flexDirection: 'row', marginTop: 10, marginLeft: 20 }}> */}
                  {/* <CheckBox
                        // style={{ width: 20, height: 20 }}
                        disabled={false}
                        value={toggleCheckBox}
                        onValueChange={(newValue) => setToggleCheckBox(newValue)}
                        tintColors={{ true: '#F15927', false: '#C1C1C1' }}
                        // onFillColor='#007aaf'
                        onCheckColor='red'
                        style={{ height: 20, width: 20, transform: [{ scaleX: 1.2 }, { scaleY: 1.2 }], borderRadius: 10 }}
                    /> */}
                  {/* <TouchableOpacity
                  //  onPress={() => [setToggleCheckBoxMain(!toggleCheckMain), setToggleCheckGuest(!toggleCheckGuest)]}
                    >
                    <Image source={toggleCheckMain ? Images.radioActivesIcon : Images.radioinactiveIcon} style={{ height: 22, width: 22 }} />
                  </TouchableOpacity> */}
                  {/* <Text style={{ marginHorizontal: 12, fontSize: 15, color: '#000' }}>I am the main guest</Text> */}
                {/* </View> */}
                {/* <View style={{ flexDirection: 'row', marginTop: 10, marginLeft: 20 }}>
                  <TouchableOpacity onPress={() => [setToggleCheckGuest(!toggleCheckGuest), setToggleCheckBoxMain(!toggleCheckMain)]}>
                    <Image source={toggleCheckGuest ? Images.radioActivesIcon : Images.radioinactiveIcon} style={{ height: 22, width: 22 }} />
                  </TouchableOpacity>
                  
                  <Text style={{ marginHorizontal: 12, fontSize: 15, color: '#000' }}>Want to book for someone else </Text>
                </View> */}
                {/* <TextView
                    placeholder={'Are you an agency?'}
                    value={AreYouAgency}
                    onChangeText={(e) => SetAreYouAgency(e)}
                    isNumeric={true}
                /> */}
                <Dropdown
                  style={{
                    // height:40,
                    // marginHorizontal:20,
                    paddingLeft: 15,
                    paddingRight: 15,
                    height: 50,
                    margin: 10,
                    backgroundColor: color.appTextBackgoundColor,
                    borderRadius: 10,
                    marginLeft: 20,
                    marginRight: 20,
                    fontSize: 15,
                    marginTop: 20
                  }}
                  placeholderStyle={{ color: color.appTextColor }}
                  itemTextStyle={{ color: '#000' }}
                  selectedTextProps={{ style: { color: '#000' } }}
                  data={agency || []}
                  search={false}
                  maxHeight={300}
                  labelField="label"
                  valueField="value"
                  placeholder="Are you an agency?"
                  // searchPlaceholder="5 guests"
                  value={value1}

                  onChange={item =>
                    setValue1(item.label)
                  } />

              </ScrollView>
              <View
                style={{
                  borderBottomWidth: 1,
                  marginVertical: 10,
                  borderBottomColor: '#E9E9E9',

                }}
              />
              {/* {
                toggleCheckGuest ?

                  <ScrollView contentContainerStyle={{ marginTop: 10 }}>
                    <Text
                      style={{
                        marginTop: 10,
                        marginHorizontal: 20,
                        color: color.primaryColorBlack,
                        fontSize: 20,
                        fontWeight: '600',
                      }}>
                      Guest's Data
                    </Text>
                    <TextView
                      placeholder={'First Name'}
                      value={guestFirstName}
                      onChangeText={onInputChangeGuestFirstName}
                    />
                    <TextView
                      placeholder={'Last Name'}
                      value={guestLastName}
                      onChangeText={onInputChangeGuestLastName}
                    />

                    <View style={{ flexDirection: 'row', justifyContent: 'flex-start', alignItems: 'center', marginHorizontal: 20 }}>

                      <View style={{ flexDirection: 'row', alignItems: 'center', justifyContent: 'flex-start', marginRight: 0, width: '35%', }}>
                        <View style={{
                          backgroundColor: color.appTextBackgoundColor, flexDirection: 'row', alignItems: 'center',
                          padding: 10,
                          height: 50,
                          borderRadius: 8, marginTop: 10
                        }}>
                          <CountryPicker
                            // {...{
                            //   onSelect,
                            // }}
                            onSelect={onSelectGuest}
                            visible={guestVisible}
                            countryCode={guestCountryCode}
                            withFilter={true}
                          // containerButtonStyle={{backgroundColor:'#fff'}}

                          />

                          <Text style={{ color: '#000' }}>+{guestCountryModal}</Text>
                        </View>
                      </View>
                      <TextInput
                        placeholder={'Phone Number'}
                        value={guestPhoneNumber}
                        onChangeText={(e) => setGuestPhoneNumber(e.replace(/[^0-9]/g, ''))}
                        keyboardType={'numeric'}
                        maxLength={12}
                        style={{
                          height: 50,
                          width: '65%',
                          // margin: 10,
                          marginTop: 10,
                          backgroundColor: color.appTextBackgoundColor,
                          borderRadius: 10,
                          paddingHorizontal: 14,
                          fontSize: 15, color: '#000'
                        }} placeholderTextColor={color.appTextColor}
                      />



                    </View>
                    <View style={{ marginTop: 10 }}>

                      <TextInput
                        placeholder={'E-Mail'}
                        value={guestEmail}
                        onChangeText={(e) => setGuestEmail(e)}
                        style={{
                          paddingLeft: 15,
                          height: 50,
                          margin: 10,
                          backgroundColor: color.appTextBackgoundColor,
                          borderRadius: 10,
                          marginLeft: 20,
                          marginRight: 20,
                          fontSize: 15, color: '#000'
                        }}
                        keyboardType='email-address'
                        placeholderTextColor={color.appTextColor}
                      />
                    </View>
                    <TextView
                      placeholder={'address'}
                      value={guestAddress}
                      onChangeText={(e) => setGuestAddress(e)}
                    />
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
                        paddingRight: 15
                      }}
                      placeholderStyle={color.appTextColor}
                      selectedTextProps={{ style: { color: '#000' } }}
                      itemTextStyle={{ color: '#000' }}
                      // inputSearchStyle={styles.inputSearchStyle}
                      // iconStyle={styles.iconStyle}
                      data={bookingData.country || []}
                      search={true}
                      maxHeight={300}
                      labelField="label"
                      valueField="value"
                      placeholder="Country"
                      searchPlaceholder="Search Country"
                      value={guestValue}
                      onChange={item => {
                        setGuestValue(item.label);
                        setGuestPlaceHolder(item.label)
                      }} />



                    <TextView
                      placeholder={'province'}
                      value={guestProvince}
                      onChangeText={(e) => setGuestProvince(e)}
                    />
                    <TextView
                      placeholder={'City'}
                      value={guestCity}
                      onChangeText={(e) => setGuestCity(e)}
                    />
                    <TextView
                      placeholder={'Postal Code'}
                      value={guestPostalCode}
                      onChangeText={(e) => setGuestPostalCode(e.replace(/[^0-9]/g, ''))}

                      isNumeric={true}
                      maxLength={6}
                    // isNumeric={true}
                    />

                    <View
                      style={{
                        borderBottomWidth: 1,
                        marginVertical: 10,
                        borderBottomColor: '#E9E9E9',

                      }}
                    />
                  </ScrollView>
                  : null
              } */}
              {/* <View
                style={{
                  width: width,
                  // height: height / 7,
                  borderColor: '#E9E9E9',

                  // marginTop: 55,
                }}>
                <Text
                  style={{
                    // marginTop: 10,
                    color: color.primaryColorBlack,
                    marginHorizontal: 20,
                    fontWeight: '600',
                  }}>
                  OFFERS
                </Text>
                <TouchableOpacity
                  // onPress={() =>
                  //   props.navigation.navigate('Offers', { propertyid: id,price:price,convenceFee:convenceFee })
                  // }
                  onPress={() =>
                    // props.navigation.navigate('Offers', { propertyid: id, price: price, convenceFee: convenceFee,book:route?.params?.bookType })
                    props.navigation.navigate('Offers', { propertyid: route.params.propertyid, price: route.params.price, convenceFee: convenceFee, bookType: route?.params?.bookType, loyalty_points: route.params.loyalty_points, points_amount: route?.params?.points_amount })
                  }
                  style={{
                    borderRadius: 10,
                    marginTop: 10,
                    height: 60,
                    marginHorizontal: 20,
                    padding: 16,
                    backgroundColor: color.appLightOrangeColor,
                    borderColor: color.appOrangeColor,
                    borderWidth: 2,
                    flexDirection: 'row',
                    justifyContent: 'space-between',
                    alignItems: 'center',
                  }}>
                  <Text style={{ color: color.appOrangeColor, fontSize: 14 }}>
                    Apply coupon code
                  </Text>
                  <Image
                    source={Images.ArrowIcons}
                    style={{ marginHorizontal: 10 }}
                  />
                </TouchableOpacity>
              </View>
              <View
                style={{
                  borderBottomWidth: 1,
                  marginVertical: 10,
                  borderBottomColor: '#E9E9E9',

                }}
              /> */}
              <View
                style={{
                  // height: height / 4,
                  // borderBottomWidth: 1,
                  borderColor: color.appTextBackgoundColor,
                  marginHorizontal: 20,
                  // marginTop: 6,
                }}>
                <Text
                  style={{
                    // marginTop: 10,
                    fontSize: 14,
                    color: color.primaryColorBlack,
                    fontWeight: '600',
                  }}>
                  PRICE DETAIL
                </Text>
                <View
                  style={{
                    justifyContent: 'space-between',
                    flexDirection: 'row',
                    marginTop: 5,
                  }}>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 16,
                      color: color.appTextColor,
                    }}>
                    NGN {bookingsData?.per_night_price}.00 X {bookingsData?.total_days} 
                    {/* {route.params.price == '' ? price : route.params.price}.00 x {dayDifference} nights */}
                  
                  </Text>
                  <Text
                    style={{
                      fontSize: 16,
                      fontWeight: '500',
                      color: color.primaryColorBlack,
                    }}>
                    NGN {bookingsData?.per_night_price * bookingsData?.total_days}
                    {/* NGN {price * dayDifference} */}
                  </Text>
                </View>
                <View
                  style={{
                    justifyContent: 'space-between',
                    flexDirection: 'row',
                    marginTop: 5,
                  }}>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 16,
                      color: color.appTextColor,
                    }}>
                    Convence fee
                  </Text>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 16,
                      color: color.primaryColorBlack,
                    }}>
                    NGN {bookingsData?.security_deposite == null ? '0' : bookingsData?.security_deposite}
                    {/* {convenceFee == null
                      ? 0
                      : convenceFee} */}
                  </Text>
                </View>
                <View
                  style={{
                    justifyContent: 'space-between',
                    flexDirection: 'row',
                    marginTop: 5,
                  }}>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 16,
                      color: color.appTextColor,
                    }}>
                    Extra Amount
                  </Text>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 16,
                      color: color.primaryColorBlack,
                    }}>
                    {/* NGN {extraAmount} */}
                    NGN {extraAmount * 1}
                  </Text>
                </View>
                {applyCoupon === undefined ? null : (
                  <View
                    style={{
                      justifyContent: 'space-between',
                      flexDirection: 'row',
                      marginTop: 5,
                    }}>
                    <Text
                      style={{
                        fontWeight: '500',
                        fontSize: 16,
                        color: color.appTextColor,
                      }}>
                      Discount
                    </Text>
                    <Text
                      style={{
                        fontWeight: '500',
                        fontSize: 16,
                        color: color.primaryColorBlack,
                      }}>
                      NGN {
                        route?.params?.myId == null ? "0" : (route.params.price == '' ? price : route.params.price * dayDifference / route?.params?.myId * 1)
                      }
                      {/* NGN {(price*dayDifference/discount)} */}
                    </Text>
                  </View>
                )}
                <View
                  style={{
                    justifyContent: 'space-between',
                    flexDirection: 'row',
                    marginTop: 5,
                  }}>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 16,
                      color: color.appTextColor,
                    }}>
                    Total
                  </Text>
                  <Text
                    style={{
                      fontWeight: '500',
                      fontSize: 16,
                      color: color.primaryColorBlack,
                    }}>

                    {
                      // redeemCheck ? route?.params?.myId == null ? ((route.params.price == '' ? price : route.params.price * dayDifference) + (convenceFee * 1) + (extraAmount * 1)) - (route?.params?.points_amount == undefined ? 0 : route?.params?.points_amount) :
                      //   ((route.params.price == '' ? price : route.params.price * dayDifference) + (convenceFee * 1) + (extraAmount * 1) - (route.params.price == '' ? price : route.params.price * dayDifference / route?.params?.myId)) - (route?.params?.points_amount == undefined ? 0 : route?.params?.points_amount) : */}

                      // route?.params?.myId == null ? (route.params.price == '' ? price : route.params.price * dayDifference) + (convenceFee * 1) + (extraAmount * 1) :
                      //   (route.params.price == '' ? price : route.params.price * dayDifference) + (convenceFee * 1) + (extraAmount * 1) - (route.params.price == '' ? price : route.params.price * dayDifference / route?.params?.myId)
bookingsData?.total_booking_amount
                    }

                  </Text>
                </View>

              </View>
              {/* <View
                style={{
                  borderBottomWidth: 1,
                  // marginTop: 50,
                  marginVertical: 10,
                  borderBottomColor: '#E9E9E9',
                }}
              /> */}
              {/* <View style={{ marginTop: 0 }}>
                <Text
                  style={{
                    fontSize: 14,
                    marginHorizontal: 20,
                    // marginTop: 8,
                    color: color.primaryColorBlack,
                    fontWeight: '600',
                    marginBottom: 8,
                  }}>
                  OPTIONAL SERVICES
                </Text>
                <View style={{ height: serviceHieght }}>
                  <FlatList
                    data={services}
                    renderItem={renderServicesItem}
                    keyExtractor={(item, index) => index.toString()}
                    scrollEnabled={false}
                  />
                  {services.length > 3 && serviceHieght == 170 ?
                    <Text style={{ marginHorizontal: 20, fontSize: 16, color: color.appOrangeColor, fontWeight: '600', alignSelf: 'flex-end' }} onPress={() => { setServiceHieght('auto') }}>more</Text>
                    :
                    <Text style={{ marginHorizontal: 20, fontSize: 16, color: color.appOrangeColor, fontWeight: '600', alignSelf: 'flex-end' }} onPress={() => { setServiceHieght(170) }}>Less</Text>
                  }
                </View>
              </View> */}
              <View style={{ borderWidth: 0.5, borderColor: '#E9E9E9', marginVertical: 10 }} />



              <ScrollView contentContainerStyle={{ flexGrow: 1 }}>
                {/* <View style={{ marginHorizontal: 20, marginTop: 15 }}>
                  <Text style={{ color: color.primaryColorBlack, fontWeight: '600' }}>
                    PAYMENT METHOD
                  </Text>
                  <View style={{ flexDirection: 'row', marginTop: 10 }}>
                    
                  </View>
                  <View style={{ flexDirection: 'row', marginTop: 10, marginBottom: 20 }}>
                    <TouchableOpacity
                      onPress={() => [setBankMode(true), setToggleCheckBox(false)]}>
                      <Image
                        source={
                          bankMode ? Images.radioActivesIcon : Images.radioinactiveIcon
                        }
                        style={{ height: 24, width: 24 }}
                      />
                    </TouchableOpacity>
                    <Text
                      style={{
                        paddingLeft: 10,
                        paddingRight: 10,
                        fontSize: 15,
                        color: color.primaryColorBlack,
                      }}>
                      Credit Card / Debit Card
                    </Text>
                  </View>
                </View> */}
                {/* <View style={{ borderWidth: 0.5, borderColor: '#e1e1e1' }} /> */}
                {bankMode ? (
                  // <KeyboardAwareView>
                  //   <View>
                  //     <Text
                  //       style={{
                  //         marginHorizontal: 20,
                  //         marginTop: width * (15 / 375),
                  //         color: color.primaryColorBlack,
                  //         fontWeight: '600',
                  //       }}>
                  //       Enter Your Card Detail
                  //     </Text>
                  //     <TextView
                  //       placeholder={'Card Holder Name'}
                  //       value={holderName}
                  //       onChangeText={onInputChange}
                  //     />
                  //     <TextView
                  //       placeholder={'Card Number'}
                  //       value={cardNumber}
                  //       onChangeText={e => setCardNumber(e.replace(/[^0-9]/g, ''))}
                  //       isNumeric={true}
                  //       maxLength={16}
                  //     />
                  //     <View style={{ flexDirection: 'row', width: width / 2 }}>
                  //       <TextInput
                  //         placeholder={'Exp. Month'}
                  //         value={month}
                  //         onChangeText={e => setMonth(e.replace(/[^0-9]/g, ''))}
                  //         keyboardType='numeric'
                  //         maxLength={2}
                  //         style={styles.input}
                  //         placeholderTextColor={'gray'}


                  //       />

                  //       <TextInput
                  //         placeholder={'Exp. Year'}
                  //         value={year}
                  //         onChangeText={e => setYear(e.replace(/[^0-9]/g, ''))}
                  //         maxLength={4}
                  //         style={styles.input}
                  //         keyboardType='numeric'
                  //         placeholderTextColor={'gray'}
                  //       />
                  //     </View>
                  //     <TextView
                  //       placeholder={'CVV'}
                  //       value={cvv}
                  //       onChangeText={e => setCvv(e.replace(/[^0-9]/g, ''))}
                  //       isNumeric={true}
                  //       maxLength={3}

                  //     />
                  //     <TouchableOpacity
                  //       //  onPress={showAlert}
                  //       onPress={() => bookNowHandler()}
                  //       style={{
                  //         marginTop: 10,
                  //         backgroundColor: color.appOrangeColor,
                  //         width: '90%',
                  //         padding: 10,
                  //         borderRadius: 10,
                  //         justifyContent: 'center',
                  //         height: 50,
                  //         marginHorizontal: 20,
                  //       }}>
                  //       <View
                  //         style={{
                  //           position: 'absolute',
                  //           flex: 1,
                  //         }}>
                  //         <Image
                  //           source={Images.whiteDot}
                  //           style={{
                  //             paddingLeft: 70,
                  //             width: 25,
                  //             height: 25,
                  //             resizeMode: 'contain',
                  //           }}
                  //         />
                  //       </View>
                  //       <Text
                  //         style={{
                  //           textAlign: 'center',
                  //           fontSize: 15,
                  //           color: color.appWhiteColor,
                  //         }}>
                  //         Book Now
                  //       </Text>
                  //     </TouchableOpacity>
                  //     {
                  //       loader ? <View style={{ top: -36, right: -65 }}><ActivityIndicator size={'small'} color='#fff' /></View> : null
                  //     }
                  //   </View>
                  // </KeyboardAwareView>
                  <View>

                  </View>
                ) : null}
                {/* {toggleCheckBox ? (
                  <View style={{ marginHorizontal: 20, marginTop: 20 }}>
                    <Text style={{ color: '#000', fontSize: 20, fontWeight: '600' }}>
                      If you choose this payment option, the accommodation will be
                      pre-reserved for you. However, to confirm the booking you will
                      have to make a bank transfer deposit to Shortlet Rentals bank
                      account-Zenith Bank- Account number- 1214818652. Send payment slip
                      via Whatsapp to +234 912 287 7657. Thank you.
                    </Text>
                  </View>
                ) : null} */}
                {pay && (
                  <View style={{ flex: 1 }}>
                    <Paystack
                      paystackKey="pk_test_c7bf5a4f97f2ad9e0dc1d77fb6f92aa21769ece0"
                      // paystackKey="pk_live_21147ca40d855a646b54e506fb8baa775d8fa131"
                      amount={bookingsData?.total_booking_amount} 
                      billingEmail={Email}
                      billingMobile={PhoneNumber}
                      activityIndicatorColor="green"

                      onCancel={(e) => {
                        // handle response here
                        // Toast.show("Transaction Cancelled!!", {
                        //   duration: Toast.durations.LONG,
                        // });
                        console.log('transaction Cancelled');
                      }}
                      
                      onSuccess={(response) => {
                        // handle response here

                        const responseObject = response["transactionRef"]["message"];
                        console.log('resop', response, 'response object', responseObject)
                        if (responseObject === "Approved") {
                          Toast.show({ type: 'success', text1: "Transaction Approved!!" }
                            // , {
                            //   // duration: Toast.durations.LONG,
                            // }
                          );
                          PaymentSuccessApi(response)
                          setPay(false)

                        }
                      }}
                      autoStart={pay}
                    />
                  </View>
                )}

              </ScrollView>

              {/* <View */}
                {/* // style={{ marginTop: 10, marginHorizontal: 20, flexDirection: 'row', justifyContent: 'flex-start', alignItems: 'center' }}> */}
                {/* <TouchableOpacity
                  activeOpacity={0.5}
                  onPress={() => {
                    setAgreeTerms(!agreeTerms)
                  }}>
                  {agreeTerms ? (
                    <Image
                      source={Images.checkfull}
                      style={{ height: 24, width: 24 }}
                    />
                  ) : (
                    <Image
                      source={Images.checkblank}
                      style={{ height: 24, width: 24 }}
                    />
                  )}
                </TouchableOpacity> */}
                {/* <Text style={{ marginLeft: 12, alignItems: 'center', color: '#000' }}>
                  I have read and I agree with the
                  <TouchableOpacity onPress={() => Linking.openURL(`${MAIN_URL}/term-of-use`)}><Text style={{ color: color.appOrangeColor }}>terms and conditions</Text></TouchableOpacity>,
                  <TouchableOpacity onPress={() => Linking.openURL(`${MAIN_URL}/privacy-policy`)}><Text style={{ color: color.appOrangeColor }}> privacy policy</Text></TouchableOpacity> <Text>and</Text>
                  <TouchableOpacity onPress={() => Linking.openURL(`${MAIN_URL}/cancellation-policy`)}><Text style={{ color: color.appOrangeColor }}>Cancellation Policy</Text></TouchableOpacity>
                </Text> */}
              {/* </View> */}
              {/* <View
                style={{ marginTop: 10, marginHorizontal: 20, flexDirection: 'row' }}>
                <TouchableOpacity
                  activeOpacity={0.5}
                  onPress={() => {
                    setOfferTerms(!offerTerms)
                  }}>
                  {offerTerms ? (
                    <Image
                      source={Images.checkfull}
                      style={{ height: 24, width: 24 }}
                    />
                  ) : (
                    <Image
                      source={Images.checkblank}
                      style={{ height: 24, width: 24 }}
                    />
                  )}
                </TouchableOpacity>
                <Text style={{ marginHorizontal: 12, color: '#000' }}>
                  Send me special offers and promotions
                </Text>
              </View>
              <View
                style={{ marginTop: 10, marginHorizontal: 20, flexDirection: 'row', alignItems: 'center' }}>
                <TouchableOpacity
                  activeOpacity={0.5}
                  onPress={() => {
                    setRedeemCheck(!redeemCheck)
                    // redeemTotal(1000 - extraRedeem);
                  }}>
                  {redeemCheck ? (
                    <Image
                      source={Images.checkfull}
                      style={{ height: 24, width: 24 }}
                    />
                  ) : (
                    <Image
                      source={Images.checkblank}
                      style={{ height: 24, width: 24 }}
                    />
                  )}
                </TouchableOpacity>
                <Text style={{ marginHorizontal: 12, color: '#000' }}>
                  Want to redeem from Loyalty points , Your {route?.params?.loyalty_points == undefined || route?.params?.loyalty_points == '' ? 0 : route?.params?.loyalty_points} LP in NGN is {route?.params?.points_amount == undefined || route?.params?.points_amount == "" ? 0 : route?.params?.points_amount}
                </Text>
              </View> */}
              <View style={{ marginHorizontal: 20 }}>
                {/* {
                  route?.params?.bookType === "Reserve" ?
                    <TouchableOpacity
                      onPress={() => {

                        // handleValidationReserveNow()
                        handleValidationPayNow()
                      }}

                      style={{
                        marginTop: 20,
                        backgroundColor: color.appOrangeColor,
                        // width: width - 30,
                        padding: 10,
                        borderRadius: 10,
                        justifyContent: 'center',
                        // marginLeft: 15,
                        height: 50,
                      }}>
                      <View
                        style={{
                          position: 'absolute',
                          // flex: 1,
                          // flexDirection:'row'
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
                      <View style={{ flexDirection: 'row', alignSelf: 'center', justifyContent: 'flex-start' }}>
                        <Text
                          style={{
                            textAlign: 'center',
                            fontSize: 15,
                            color: color.appWhiteColor,
                            marginHorizontal: 20
                          }}>
                          Reserve</Text>
                        {
                          loader ? <View style={{}}><ActivityIndicator size={'small'} color='#fff' /></View> : null
                        }
                      </View>
                    </TouchableOpacity>
                    : */}
                    <TouchableOpacity
                      onPress={() => {
                       mobileBookingApiReserve()
                      }}

                      style={{
                        marginTop: 20,
                        backgroundColor: color.appOrangeColor,
                        // width: width - 30,
                        padding: 10,
                        borderRadius: 10,
                        justifyContent: 'center',
                        // marginLeft: 15,
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
                      <View style={{ flexDirection: 'row', alignSelf: 'center', justifyContent: 'flex-start' }}>
                        <Text
                          style={{
                            textAlign: 'center',
                            fontSize: 15,
                            color: color.appWhiteColor,
                            marginHorizontal: 20
                          }}>
                          Pay Now</Text>
                        {
                          loader ? <View style={{}}><ActivityIndicator size={'small'} color='#fff' /></View> : null
                        }
                      </View>

                    </TouchableOpacity>
                {/* } */}
              </View>

              <View style={{ borderWidth: 0.5, borderColor: '#E9E9E9', marginVertical: 10 }} />
              <View
                style={{
                  width: width,
                  // height: height / 5,
                  alignSelf: 'center',
                  marginTop: 10,
                }}>
                <Text
                  style={{
                    marginTop: 10,
                    marginHorizontal: 20,
                    color: color.primaryColorBlack,
                    fontSize: 14,
                    fontWeight: '600',
                  }}>
                  YOUR TRIP
                </Text>
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
                      width: width - 42,
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
                      {/* <Modal
                    visible={modalVisible}
                    transparent
                    onBackdropPress={() => setModalVisible(false)}
                  // style={{ backgroundColor: 'red', height: 300, width: '100%' }}
                  >
                    <Calendar
                      // current={new Date()}
                      // current={date == 'Select Date' ? new Date() : moment(date).format('YYYY-MM-DD')}


                      minDate={new Date()}
                      // minDate={date == 'Select Date' ? new Date() : moment(date).format('YYYY-MM-DD')}



                      maxDate={moment(dateLocal).format('YYYY-MM-DD')}
                      // maxDate={dateLocal == 'Select Date' ? "2030-09-09" : moment(dateLocal).format('YYYY-MM-DD')}
                      onDayPress={onDayPress}
                      onDayLongPress={day => {
                        console.log('selected day', day);
                      }}
                      enableSwipeMonths={true}
                    />
                    
                  </Modal> */}
                      {/* <TouchableOpacity
                     onPress={() => setModalVisible(!modalVisible)}
                    > */}
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
                        {moment(bookingsData?.from_date).format('YYYY-MM-DD')}
                        {/* {bookingsData?.from_date} */}
                      </Text>




                      {/* </TouchableOpacity> */}
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
                      {/* <Modal
                    visible={modal}
                    transparent
                    onBackdropPress={() => setModal(false)}
                  >
                    <Calendar
                     
                      minDate={moment(date).format('YYYY-MM-DD')}
                     
                      onDayPress={onDayPressLocal}
                      style={{ marginHorizontal: 20 }}
                    />
                  </Modal> */}
                      {/* <TouchableOpacity
                    onPress={() => {
                      day => setDateLocal(day), setModal(!modal);
                    }}> */}
                      <Text
                        style={{
                          color: color.primaryColorBlack,
                          fontWeight: '600',
                        }}>
                        Check out
                      </Text>


                      <Text style={{ color: color.appTextColor }}>
                        {moment(bookingsData?.to_date).format('YYYY-MM-DD')}
                        {/* {bookingsData?.to_date} */}
                      </Text>



                      {/* </TouchableOpacity> */}
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


                    <TouchableOpacity

                      onPress={() => setGuestModal(!guestModal)}
                      style={{
                        flexDirection: 'row',
                        justifyContent: 'space-between',
                      }}>
                      {/* <Text style={{ color: '#000' }}>{count1 + count2 + count3 + count4} guest</Text> */}
                      <Text style={{ color: '#000' }}>{bookingsData?.no_of_adult_guest + bookingsData?.no_of_children_guest + bookingsData?.no_of_babies_guest + bookingsData?.no_of_pet} guest</Text>
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
                        marginHorizontal: 10,
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
                        {/* <TouchableOpacity
                      activeOpacity={0.9}
                      onPress={counter1}
                      style={styles.AddingBackground}>
                      <Image
                        source={Images.minusIcon}
                        style={{ height: 20, width: 20 }}
                      />
                    </TouchableOpacity> */}
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
                            {bookingsData?.no_of_adult_guest}
                          </Text>
                        </View>
                        {/* <TouchableOpacity
                      activeOpacity={0.9}
                      onPress={() => setCount1(count1 + 1)}
                      style={styles.AddingBackground}>
                    
                      <Image
                        source={Images.plusIcon}
                        style={{ height: 20, width: 20 }}
                      />
                    </TouchableOpacity> */}
                      </View>
                    </View>
                    <View
                      style={{
                        flexDirection: 'row',
                        justifyContent: 'space-between',
                        marginHorizontal: 10,
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
                        {/* <TouchableOpacity
                      activeOpacity={0.9}
                      onPress={counter2}
                      style={styles.AddingBackground}>
                     
                      <Image
                        source={Images.minusIcon}
                        style={{ height: 20, width: 20 }}
                      />
                    </TouchableOpacity> */}
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
                            {bookingsData?.no_of_children_guest}
                          </Text>
                        </View>
                        {/* <TouchableOpacity
                      activeOpacity={0.9}
                      onPress={() => setCount2(count2 + 1)}
                      style={styles.AddingBackground}>
                     
                      <Image
                        source={Images.plusIcon}
                        style={{ height: 20, width: 20 }}
                      />
                    </TouchableOpacity> */}
                      </View>
                    </View>
                    <View
                      style={{
                        flexDirection: 'row',
                        justifyContent: 'space-between',
                        marginHorizontal: 10,
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
                        {/* <TouchableOpacity
                      activeOpacity={0.9}
                      onPress={counter3}
                      style={styles.AddingBackground}>
                      
                      <Image
                        source={Images.minusIcon}
                        style={{ height: 20, width: 20 }}
                      />
                    </TouchableOpacity> */}
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
                            {bookingsData?.no_of_babies_guest}
                          </Text>
                        </View>
                        {/* <TouchableOpacity
                      activeOpacity={0.9}
                      onPress={() => setCount3(count3 + 1)}
                      style={styles.AddingBackground}>
                      
                      <Image
                        source={Images.plusIcon}
                        style={{ height: 20, width: 20 }}
                      />
                    </TouchableOpacity> */}
                      </View>
                    </View>
                    <View
                      style={{
                        flexDirection: 'row',
                        justifyContent: 'space-between',
                        marginHorizontal: 10,
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
                        {/* <TouchableOpacity
                      activeOpacity={0.5}
                      onPress={counter4}
                      style={styles.AddingBackground}>
                     
                      <Image
                        source={Images.minusIcon}
                        style={{ height: 20, width: 20 }}
                      />
                    </TouchableOpacity> */}
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
                            {bookingsData?.no_of_pet}
                          </Text>
                        </View>
                        {/* <TouchableOpacity
                      activeOpacity={0.9}
                      onPress={() => setCount4(count4 + 1)}
                      style={styles.AddingBackground}>
                     
                      <Image
                        source={Images.plusIcon}
                        style={{ height: 20, width: 20 }}
                      />
                    </TouchableOpacity> */}
                      </View>
                    </View>
                  </View>
                ) : null}
                {/* <View
                  style={{
                    borderBottomWidth: 1,
                    marginVertical: 10,
                    borderBottomColor: '#E9E9E9',

                  }}
                /> */}
              </View>
              {/* <View
                style={{
                  width: width,
                  // height: height / 6,
                  borderColor: 6,
                  marginBottom: 50,
                }}>
                {bookingData.personalData === undefined ? (
                  <TouchableOpacity
                    // onPress={() =>
                    //   props.navigation.navigate('AddPersonalData', { propertyid: id })
                    // }
                    onPress={() =>
                      props.navigation.navigate('AddPersonalData', {
                        propertyid: route.params.propertyid, price: route.params.price, convenceFee: convenceFee, myId: route?.params?.myId, max_Gest: max_Gest, book: route?.params?.bookType, loyalty_points: route.params.loyalty_points, points_amount: route?.params?.points_amount
                      })
                    }
                    style={{
                      borderRadius: 10,
                      marginTop: 10,
                      height: 60,
                      marginHorizontal: 20,
                      paddingHorizontal: 12,
                      backgroundColor: color.appLightOrangeColor,
                      borderColor: color.appOrangeColor,
                      borderWidth: 2,
                      flexDirection: 'row',
                      justifyContent: 'space-between',
                      alignItems: 'center',
                    }}>
                    <Text style={{ color: color.appOrangeColor, fontSize: 14 }}>
                      Add Personal Data
                    </Text>
                    <Image source={Images.ArrowIcons} />
                  </TouchableOpacity>
                ) : (
                  <View style={{
                    width: width,
                    //  height: height / 6
                  }}>
                    <View style={{ width: width - 30, alignSelf: 'center' }}>
                      <View
                        style={{
                          flexDirection: 'row',
                          justifyContent: 'space-between',
                          // marginTop: 10,
                        }}>
                        <Text
                          style={{
                            fontSize: 15,
                            fontWeight: '500',
                            color: color.primaryColorBlack,
                          }}>
                          PERSONAL INFO
                        </Text>
                        <TouchableOpacity
                          // onPress={() =>
                          //   props.navigation.navigate('AddPersonalData', {
                          //     propertyid:route?.params?.id
                          //   })
                          // }
                          onPress={() =>
                            props.navigation.navigate('AddPersonalData', {
                              propertyid: route.params.propertyid, price: route.params.price, convenceFee: convenceFee, myId: route?.params?.myId, max_Gest: max_Gest, book: route?.params?.bookType, loyalty_points: route.params.loyalty_points, points_amount: route?.params?.points_amount

                            })
                          }
                        >

                          <Text style={{ color: color.appOrangeColor, fontSize: 15 }}>
                            Edit
                          </Text>
                        </TouchableOpacity>
                      </View>
                      <Text
                        style={{
                          fontSize: 15,
                          fontWeight: '500',
                          color: color.appTextColor,
                          marginTop: 5,
                        }}>
                        {bookingData.personalData.fullName}
                      </Text>
                      <View
                        style={{
                          flexDirection: 'row',
                          marginTop: 10,
                          marginBottom: 5,
                          justifyContent: 'flex-start',
                          alignItems: 'center',
                        }}>
                        <Image
                          source={Images.LocationIcon}
                          style={{
                            width: 16,
                            height: 16,
                            resizeMode: 'contain',
                            marginRight: 6,
                          }}
                        />
                        <Text style={{ color: color.primaryColorBlack }}>
                          {bookingData.personalData.Address} {bookingData.personalData.City} {bookingData.personalData.PostalCode}
                        </Text>
                      </View>
                      <View
                        style={{
                          flexDirection: 'row',
                          marginTop: 4,
                          marginBottom: 5,
                          justifyContent: 'flex-start',
                          alignItems: 'center',
                        }}>
                        <Image
                          source={Images.CallIcons}
                          style={{
                            width: 16,
                            height: 16,
                            resizeMode: 'contain',
                            marginRight: 6,
                          }}
                        />
                        <Text style={{ color: color.primaryColorBlack }}>
                          {bookingData.personalData.PhoneNumber}
                        </Text>
                      </View>
                      <View
                        style={{
                          flexDirection: 'row',
                          marginTop: 4,
                          justifyContent: 'flex-start',
                          alignItems: 'center',
                        }}>
                        <Image
                          source={Images.email}
                          style={{
                            width: 16,
                            height: 16,
                            resizeMode: 'contain',
                            marginRight: 6,
                          }}
                        />
                        <Text
                          style={{ color: color.primaryColorBlack, marginLeft: 3 }}>
                          {bookingData.personalData.Email}
                        </Text>
                      </View>
                    </View>
                    <View
                      style={{
                        borderWidth: 0.5,
                        borderColor: '#e9e9e9',
                        marginVertical: 10,
                      }}
                    />
                  </View>
                )}
                {bookingData.guestData === undefined ? (
                  <TouchableOpacity
                    // onPress={() =>
                    //   props.navigation.navigate('AddGuestData', { propertyid: id })
                    // }
                    onPress={() =>
                      props.navigation.navigate('AddGuestData', {
                        propertyid: route.params.propertyid, price: route.params.price, convenceFee: convenceFee, myId: route?.params?.myId, max_Gest: max_Gest, book: route?.params?.bookType, loyalty_points: route.params.loyalty_points, points_amount: route?.params?.points_amount
                      })
                    }
                    style={{
                      borderRadius: 10,
                      marginTop: 20,
                      height: 60,
                      marginHorizontal: 20,
                      paddingHorizontal: 12,
                      backgroundColor: color.appLightOrangeColor,
                      borderColor: color.appOrangeColor,
                      borderWidth: 2,
                      flexDirection: 'row',
                      justifyContent: 'space-between',
                      alignItems: 'center',
                      marginBottom: 60,
                    }}>
                    <Text style={{ color: color.appOrangeColor, fontSize: 14 }}>
                      Guests' Data
                    </Text>
                    <Image source={Images.ArrowIcons} />
                  </TouchableOpacity>
                ) : (
                  <View
                    style={{
                      width: width - 30,
                      alignSelf: 'center',
                      // marginTop: 20,
                      marginHorizontal: 20,
                    }}>
                    <View
                      style={{
                        flexDirection: 'row',
                        justifyContent: 'space-between',
                        // marginTop: 10,
                      }}>
                      <Text
                        style={{
                          fontSize: 15,
                          fontWeight: '500',
                          color: color.primaryColorBlack,
                        }}>
                        GUEST INFO
                      </Text>
                      <TouchableOpacity
                        // onPress={() =>
                        //   props.navigation.navigate('AddGuestData', {
                        //     propertyid: id,
                        //   })
                        // }
                        onPress={() =>
                          props.navigation.navigate('AddGuestData', {
                            propertyid: route.params.propertyid, price: route.params.price, convenceFee: convenceFee, myId: route?.params?.myId, max_Gest: max_Gest, book: route?.params?.bookType, loyalty_points: route.params.loyalty_points, points_amount: route?.params?.points_amount
                          })
                        }
                      >
                        <Text style={{ color: color.appOrangeColor, fontSize: 15 }}>
                          Edit
                        </Text>
                      </TouchableOpacity>
                    </View>
                    <Text
                      style={{
                        fontSize: 15,
                        fontWeight: '500',
                        color: color.appTextColor,
                        marginTop: 4,
                      }}>
                      {bookingData.guestData.fullName}{' '}
                      {bookingData.guestData.lastName}
                    </Text>
                    <View
                      style={{
                        flexDirection: 'row',
                        marginTop: 10,
                        marginBottom: 5,
                        justifyContent: 'flex-start',
                        alignItems: 'center',
                      }}>
                      <Image
                        source={Images.LocationIcon}
                        style={{
                          width: 16,
                          height: 16,
                          resizeMode: 'contain',
                          marginRight: 6,
                        }}
                      />
                      <Text style={{ color: color.primaryColorBlack }}>
                        {bookingData.guestData.city}
                        {bookingData.guestData.postalCode}
                      </Text>
                    </View>
                    <View
                      style={{
                        flexDirection: 'row',
                        marginTop: 4,
                        marginBottom: 5,
                        justifyContent: 'flex-start',
                        alignItems: 'center',
                      }}>
                      <Image
                        source={Images.CallIcons}
                        style={{
                          width: 16,
                          height: 16,
                          resizeMode: 'contain',
                          marginRight: 6,
                        }}
                      />
                      <Text style={{ color: color.primaryColorBlack }}>
                        {bookingData.guestData.phoneNumber}
                      </Text>
                    </View>
                    <View
                      style={{
                        flexDirection: 'row',
                        marginTop: 4,
                        justifyContent: 'flex-start',
                        alignItems: 'center',
                      }}>
                      <Image
                        source={Images.email}
                        style={{
                          width: 16,
                          height: 16,
                          resizeMode: 'contain',
                          marginRight: 6,
                        }}
                      />
                      <Text style={{ color: color.primaryColorBlack, marginLeft: 4 }}>
                        {bookingData.guestData.email}
                      </Text>
                    </View>
                  </View>
                )}
              </View> */}
              <View style={{ marginBottom: 80 }} />
            </ScrollView>
          </View>
      }
    </>
  );
};
export default Payment;
const styles = StyleSheet.create({
  dropdown: {
    //   margin: 16,
    height: 20,
    borderBottomColor: 'gray',
    marginRight: 20,
    // borderBottomWidth: 0.5,
  },
  icon: {
    marginRight: 5,
  },
  placeholderStyle: {
    fontSize: 16,
  },
  selectedTextStyle: {
    fontSize: 16,
  },
  iconStyle: {
    width: 20,
    height: 20,
  },
  inputSearchStyle: {
    height: 40,
    fontSize: 16,
    backgroundColor: 'red',
  },
  modal: {
    backgroundColor: 'red',
    flex: 1,
    // marginTop: 100
  },
});









// import React, { useContext, useState, useEffect } from 'react';
// import axios from "axios";
// import {
//   View,
//   Image,
//   Text,
//   Alert,
//   StyleSheet,
//   ImageBackground,
//   TouchableOpacity,
//   _Text,
//   ScrollView,
//   TextInput,
//   ActivityIndicator,
// } from 'react-native';
// import { color, height, width } from '../../styles/colors';
// import Images from '../../styles/Images';
// import { commonStyles } from './../../styles/style';
// import { Colors, colors } from 'react-native/Libraries/NewAppScreen';
// import Header from '../../components/Header';
// import TextView from '../../components/TextView';
// import CheckBox from '@react-native-community/checkbox';
// import { Context as BookingContext } from '../../context/BookingContext';
// import { Context as AuthContext } from '../../context/AuthContext';
// import { useFocusEffect, useRoute } from '@react-navigation/native';
// import { Toast } from 'react-native-toast-message/lib/src/Toast';
// import moment from 'moment';
// import { Booking, BookingReserve, Booking_Reserve, User_Profile, getBooking_Reserve } from '../../network/Webconstant';
// import AsyncStorage from '@react-native-async-storage/async-storage';
// import { InteractionManager } from 'react-native';
// import { BackHandler } from 'react-native';
// import { KeyboardAwareView } from 'react-native-keyboard-aware-view';
// import { Paystack } from 'react-native-paystack-webview';
// import { getProfileData } from 'react-native-calendars/src/Profiler';
// // import RNPaystack from 'react-native-paystack';

// const Payment = props => {
//   const route = useRoute();
//   // console.log('=====================id==============', route.params.propertyid);
//   const [holderName, setHolderName] = useState('');
//   const [cardNumber, setCardNumber] = useState('');
//   const [year, setYear] = useState('');
//   const [month, setMonth] = useState('');
//   const [cvv, setCvv] = useState('');
//   const [toggleCheckBox, setToggleCheckBox] = useState(true);
//   const [bankMode, setBankMode] = useState(false);
//   const [price, setPrice] = useState('')
//   const [loader, setLoader] = useState(false)
//   const [reserveData, setReserveData] = useState('')
//   const [loaderRes, setLoaderRes] = useState(false)
//   const [pay, setPay] = useState(false);
//   const [profileUserData,setProfileUserData] = useState({})
//   const [billingDetail, setBillingDetail] = useState({
//     billingName:'',// profileUserData?.fullName,
//     billingEmail:'', //profileUserData?.email,
//     billingMobile:'',// profileUserData?.mobile,
//     amount: '' ,
//   });




//   useFocusEffect(
//     React.useCallback(() => {

//       if (route?.params?.from == "Notification") { 
//         getMobileBookingReserveApi()
//       }else{
//         getUserProfile({})
//       }

//     }, [route?.params?.ID])
//   );

//   const handler = () => {
//     route.params.from == 'Notification' ? props.navigation.navigate('Notification') : props.navigation.navigate('Booking', { propertyid: route.params.propertyid, price: route?.params?.price,loyalty_amount:route?.params?.loyalty_amount,points_amount:route?.params?.points_amount })

//   }
//   useFocusEffect(
//     React.useCallback(() => {
//       const backAction = () => {
//         handler()
//         return true;
//       };

//       const backHandler = BackHandler.addEventListener(
//         'hardwareBackPress',
//         backAction,
//       );

//       return () => backHandler.remove();
//     }, [props]))
//   let date = new Date()
//   // console.log(date.getFullYear())

//   console.log("payment prop id ",route.params.from, reserveData.total_amount ,route?.params?.total_amount)


//   // const chargeCard = ()=> {

//   // 	RNPaystack.chargeCardWithAccessCode({
//   //       cardNumber: cardNumber, 
//   //       expiryMonth: month, 
//   //       expiryYear: year, 
//   //       cvc: cvv,
//   //       accessCode: '2p3j42th639duy4'
//   //     })
//   // 	.then(response => {
//   // 	  console.log(response); // do stuff with the token
//   // 	})
//   // 	.catch(error => {
//   // 	  console.log(error); // error is a javascript Error object
//   // 	  console.log(error.message);
//   // 	  console.log(error.code);
//   // 	})

//   // }
//   const bookNowHandler = () => {
//     if (holderName.trim() == '') {
//       Toast.show({
//         type: 'error',
//         text1: 'Shortlet',
//         text2: 'Please enter card holder name',
//       });
//     } else if (cardNumber.trim() == '') {
//       Toast.show({
//         type: 'error',
//         text1: 'Shortlet',
//         text2: 'please enter card number',
//       });
//     } else if (cardNumber.length < 16) {
//       Toast.show({
//         type: 'error',
//         text1: 'Shortlet',
//         text2: 'please enter card number 16 digit',
//       });
//     } else if (cardNumber !== '') {
//       let re = /^([+]\d{2})?\d{16}$/;
//       if (re.test(cardNumber) === false) {
//         Toast.show({
//           type: 'error',
//           text1: 'Shortlet',
//           text2: 'Invalid card number',
//         });
//       } else if (month.trim() == '') {
//         Toast.show({
//           type: 'error',
//           text1: 'Shortlet',
//           text2: 'please enter month',
//         });
//       } else if (month > 12 || month < 1) {
//         Toast.show({
//           type: 'error',
//           text1: 'Shortlet',
//           text2: 'please enter valid month',
//         });
//       } else if (month !== '') {
//         let reg = /^([+]\d{2})?\d{2}$/;
//         if (reg.test(month) === false) {
//           Toast.show({
//             type: 'error',
//             text1: 'Shortlet',
//             text2: 'Invalid month',
//           });
//         } else if (year.trim() == '') {
//           Toast.show({
//             type: 'error',
//             text1: 'Shortlet',
//             text2: 'please enter  year',
//           });
//         } else if (year > 2050 || year < date.getFullYear()) {
//           Toast.show({
//             type: 'error',
//             text1: 'Shortlet',
//             text2: 'please enter valid year',
//           });
//         } else if (year !== '') {
//           let rege = /^([+]\d{2})?\d{4}$/;
//           if (rege.test(year) === false) {
//             Toast.show({
//               type: 'error',
//               text1: 'Shortlet',
//               text2: 'Invalid year',
//             });
//           } else if (cvv.trim() == '') {
//             Toast.show({
//               type: 'error',
//               text1: 'Shortlet',
//               text2: 'please enter cvv',
//             });
//           } else if (cvv < 3) {
//             Toast.show({
//               type: 'error',
//               text1: 'Shortlet',
//               text2: 'please enter 3 digits',
//             });
//           } else if (cvv !== '') {
//             let regex = /^([+]\d{2})?\d{3}$/;
//             if (regex.test(cvv) === false) {
//               Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'Invalid cvv',
//               });
//             } else {
               
//               if (route?.params?.from == "Notification") {
//                 mobileBookingApiReserve();
//               } else {
//                 mobileBookingApi();
//               }

//             }
//           }
//         }
//       }
//     }
//   };

//   const {
//     mobileBooking,
//     state: { bookingData, applyCoupon, updateBookingData },
//   } = useContext(BookingContext);
//   const {
//     state: { Token_ID },
//   } = useContext(AuthContext);


//   const mobileBookingApi = async () => {
//     setPay(false)
//     const Localtoken = await AsyncStorage.getItem('token_id');
//     let data = {
//       property_id: route?.params?.propertyid,
//       // start_date: route.params.Date,
//       // end_date: route.params.DateLocal,
//       start_date: moment(route.params.Date).format('YYYY-MM-DD'),
//       end_date: moment(route.params.DateLocal).format('YYYY-MM-DD'),
//       total_booking_amount: route?.params?.total_amount,
//       adultCount: route.params.Counter1,
//       childCount: route.params.Counter2,
//       infantCount: route.params.Counter3,
//       petCount: route.params.Counter4,
//       total_days: route.params.Day,
//       discount_code: route.params.CODE,
//       // discount_code:applyCoupon.code,
//       discount_id: route.params.ID,
//       discount_amount: route?.params?.Discount,
//       is_agency: 'No',
//       payment_method: 'credit_card',
//       book_for: bookingData.personalData.book_for,
//       policy_read: 'Yes',
//       first_name: bookingData.personalData.fullName,
//       last_name: 'kumar',
//       address: bookingData.personalData.Address,
//       // country_id:bookingData.personalData.value,
//       city: bookingData.personalData.City,
//       postal_code: bookingData.personalData.PostalCode,
//       phone_number: bookingData.personalData.PhoneNumber,
//       email: bookingData.personalData.Email,
//       comment: bookingData.personalData.Comments,
//       guest_first_name: bookingData.guestData.fullName,
//       guest_last_name: bookingData.guestData.lastName,
//       guest_address: bookingData.guestData.address,
//       guest_city: bookingData.guestData.city,
//       guest_zipcode: bookingData.guestData.postalCode,
//       guest_country_id: bookingData.guestData.value,
//       guest_phone_number: bookingData.guestData.phoneNumber,
//       guest_email: bookingData.guestData.email,
//       card_holder_name: holderName,
//       card_number: cardNumber,
//       month: month,
//       year: year,
//       cvv: cvv,
//       redeem_royalty_points: 'No',
//       loyalty_points: route.params.loyalty_points,
//       loyalty_amount: route.params.loyalty_amount,
//       // selected_options:[{"id":"3","name":"Photoshoot","value":"100000"},{"id":"4","name":"Early Check in","value":"5000"},{"id":"7","name":"Game night","value":"10000"}]
//     };
//     let headers = {
//       Accept: 'application/json',
//       Authorization: `Bearer ${Localtoken}`,
//     };

//     setLoader(true)
//     try {
//       axios({
//         method: 'post',
//         url: Booking,
//         data,
//         headers
//       }).then(
//         function (response) {
//           if (response.data.status) {
//             Toast.show({
//               type: 'success',
//               text1: 'Shortlet',
//               text2: response.data.message,
//             });
//             setPay(true)
//             setLoader(false)
//             setReserveData('')
//             setCardNumber('')
//             setCvv('')
//             setHolderName('')
//             setMonth('')
//             setYear('')
//             // props.navigation.navigate('Home')
//           } else {
//             Toast.show({
//               type: 'error',
//               text1: 'Shortlet',
//               text2: response.data.message,
//             });
//             setLoader(false)
//             // props?.navigation?.navigate('Booking')
//           }

//         }
//       )
//     } catch (error) {
//       console.log("BookingContext:", error)
//       setLoader(false)
//     }

//   }

//   const getUserProfile = async (reserveData) => {
     
//     // setLoader(true);
//     const Localtoken = await AsyncStorage.getItem('token_id');
//     // myToken = myToken ? Localtoken : null
//     try {
//         await axios({
//             method: 'get',
//             url: User_Profile,
//             headers: {
//                 Accept: 'application/json',
//                 Authorization: `Bearer ${Localtoken}`,
//             },
//         })
//             .then(function (res) {
//                 if (res.data.status) {
//                     // setLoader(false);
//                     console.log('res.data.data',res.data.data)
//                     setProfileUserData(res.data.data);

//                     setBillingDetail({
//                       billingName:res.data.data.fullname,
//                       billingEmail:res.data.data.email,
//                       billingMobile:res.data.data.mobile,
//                       amount: route.params.from === 'Notification' ? reserveData.total_amount : route?.params?.total_amount ,// route?.params?.total_amount ,
//                     })
//                     // setBillingDetail({billingEmail:res.data.data.email})
//                     // setBillingDetail({billingMobile:res.data.data.mobile})
//                     // setEditHandle(true)
//                 } else {
//                     // setLoader(false);
//                 }
//             })
           
//     } catch (error) {
//         console.log('something went wrong', error);
//         // setLoader(false);
//     }
// };
//   console.log('===========reserve data ',billingDetail,"---------",reserveData);

//   const mobileBookingApiReserve = async () => {
//     // alert("called mobile booking reserve api")
//     console.log('===========reserve data ',reserveData);
//     setPay(false)

//     // alert("===0",route.params.propertyid)
//     const Localtoken = await AsyncStorage.getItem('token_id');
//     let data = {
//       property_id: reserveData.property_id,// route?.params?.propertyid, 
//       start_date: moment(reserveData.from_date).format('YYYY-MM-DD'),
//       end_date: moment(reserveData.to_date).format('YYYY-MM-DD'),
//       total_booking_amount: reserveData.total_amount,
//       adultCount: reserveData.no_of_adult_guest,
//       childCount: reserveData.no_of_children_guest,
//       infantCount: reserveData.no_of_babies_guest,
//       petCount: reserveData.no_of_pet,
//       total_days: reserveData.total_days,
//       discount_code: reserveData.coupon_code,
//       // discount_code:applyCoupon.code,
//       discount_id: reserveData.discount_id,
//       discount_amount: reserveData.discount_amount,
//       is_agency: reserveData.is_agency,
//       payment_method: 'credit_card',
//       book_for: reserveData.book_for,
//       policy_read: reserveData.policy_read,
//       first_name: reserveData.personal_first_name,
//       last_name: reserveData.personal_last_name,
//       address: reserveData.personal_address,
//       // country_id:bookingData.personalData.value,
//       city: reserveData.personal_city,
//       postal_code: reserveData.personal_postal_code,
//       phone_number: reserveData.personal_phone_number,
//       email: reserveData.personal_email,
//       comment: reserveData.personal_comment,
//       guest_first_name: reserveData.guest_first_name,
//       guest_last_name: reserveData.guest_last_name,
//       guest_address: reserveData.guest_address,
//       guest_city: reserveData.guest_city,
//       guest_zipcode: reserveData.guest_zipcode,
//       guest_country_id: reserveData.guest_country_id,
//       guest_phone_number: reserveData.guest_phone_number,
//       guest_email: reserveData.guest_email,
//       card_holder_name: holderName,
//       card_number: cardNumber,
//       month: month,
//       year: year,
//       cvv: cvv,
//       redeem_royalty_points: 'No',
//       loyalty_points: reserveData.loyalty_points,
//       loyalty_amount: reserveData.loyalty_amount,
//       booking_reserve: 'reserve',
//       notification_id: route?.params?.notificationID
//       // selected_options:[{"id":"3","name":"Photoshoot","value":"100000"},{"id":"4","name":"Early Check in","value":"5000"},{"id":"7","name":"Game night","value":"10000"}]
//     };
//     console.log(data)
//     let headers = {
//       Accept: 'application/json',
//       Authorization: `Bearer ${Localtoken}`,
//     };

//     // console.log("token is====== ",headers)
//     setLoader(true)
//     try {
//       axios({
//         method: 'post',
//         url: Booking,
//         data,
//         headers
//       }).then(
//         function (response) {
//           if (response.data.status) {
//             // console.log("success message",response.data.message)
//             Toast.show({
//               type: 'success',
//               text1: 'Shortlet',
//               text2: response.data.message,
//             });
//             setPay(true)
//             setLoader(false)
//           //  setReserveData('')
//             setCardNumber('')
//             setCvv('')
//             setHolderName('')
//             setMonth('')
//             setYear('')
//             // callback()
//           } else {
//             // console.log("error message", response.data.message)
//             Toast.show({
//               type: 'error',
//               text1: 'Shortlet',
//               text2: response.data.message,
//             });
//             setLoader(false)
//           }
//           // let temp = response?.data;
//           // console.log('====mobileBookingResponse', temp.data)
//           // if (temp.status === true) {
//           //   callback()
//           // }
//         }
//       )
//     } catch (error) {
//       console.log("BookingContext:", error)
//       setLoader(false)
//     }

//   }

//   const getMobileBookingReserveApi = async () => {
//     const Localtoken = await AsyncStorage.getItem('token_id');
//     setLoaderRes(true)
//     try {
//       axios({
//         method: 'post',
//         url: getBooking_Reserve,
//         data: { property_id: route?.params?.ID },
//         headers: {
//           Accept: 'application/json',
//           Authorization: `Bearer ${Localtoken}`,
//         }
//       }).then(
//         function (response) {
//           console.log('=============response getbookingresrve api', response.data);
//           if (response.data.status == 0) {
//             setReserveData(response.data)
//             setLoaderRes(false) 
//               getUserProfile(response.data)
//           } else {
//             alert(response.data.message)
//             setLoaderRes(false)
//           }
//         }
//       )
//     } catch (error) {
//       console.log('======error getmobile booking reserve', error);
//       setLoaderRes(false)
//     }
//   }
//   const onInputChange = (value) => {

//     console.log('Input value: ', value);
//     const re = /^[a-zA-Z\s]*$/;
//     if (value === "" || re.test(value)) {
//       setHolderName(value);
//     }
//   }
//   // console.log('----------------------reservedta------', reserveData);
//   return (
//     <View style={commonStyles.container}>
//       <Header
//         props={props}
//         Heading={'Payment'}
//         onPress={() =>
//           route.params.from == 'Notification' ? props.navigation.navigate('Notification') : props.navigation.navigate('Booking', { propertyid: route.params.propertyid, price: route?.params?.price,loyalty_amount:route?.params?.loyalty_amount,points_amount:route?.params?.points_amount })
//         }
//       />
//       {
//         loaderRes ? <View ><ActivityIndicator size={'large'} color='#000' /></View> :

//           <ScrollView contentContainerStyle={{ flexGrow: 1 }}>
//             <View style={{ marginHorizontal: 20, marginTop: 15 }}>
//               <Text style={{ color: color.primaryColorBlack, fontWeight: '600' }}>
//                 PAYMENT METHOD
//               </Text>
//               <View style={{ flexDirection: 'row', marginTop: 10 }}>
//                 {/* <CheckBox
//                             tintColor={'red'}
//                             onFillColor={'red'}
//                             onCheckColor='red'
//                     style={{height:20,width:20}}
//                             disabled={false}
//                             value={toggleCheckBox}
//                             onValueChange={(newValue) => setToggleCheckBox(newValue)}
                            
//                         /> */}
//                 <TouchableOpacity
//                   onPress={() => [setToggleCheckBox(true), setBankMode(false)]}>
//                   <Image
//                     source={
//                       toggleCheckBox
//                         ? Images.radioActivesIcon
//                         : Images.radioinactiveIcon
//                     }
//                     style={{ height: 24, width: 24 }}
//                   />
//                 </TouchableOpacity>
//                 <Text
//                   style={{
//                     paddingLeft: 10,
//                     paddingRight: 10,
//                     fontSize: 15,
//                     color: color.primaryColorBlack,
//                   }}>
//                   Bank transfer Shortiest Rentals Ltd
//                 </Text>
//               </View>
//               <View style={{ flexDirection: 'row', marginTop: 10, marginBottom: 20 }}>
//                 <TouchableOpacity
//                   onPress={() => [setBankMode(true), setToggleCheckBox(false)]}>
//                   <Image
//                     source={
//                       bankMode ? Images.radioActivesIcon : Images.radioinactiveIcon
//                     }
//                     style={{ height: 24, width: 24 }}
//                   />
//                 </TouchableOpacity>
//                 <Text
//                   style={{
//                     paddingLeft: 10,
//                     paddingRight: 10,
//                     fontSize: 15,
//                     color: color.primaryColorBlack,
//                   }}>
//                   Credit Card / Debit Card
//                 </Text>
//               </View>
//             </View>
//             <View style={{ borderWidth: 0.5, borderColor: '#e1e1e1' }} />
//             {bankMode ? (
//               <KeyboardAwareView>
//                 <View>
//                   <Text
//                     style={{
//                       marginHorizontal: 20,
//                       marginTop: width * (15 / 375),
//                       color: color.primaryColorBlack,
//                       fontWeight: '600',
//                     }}>
//                     Enter Your Card Detail
//                   </Text>
//                   <TextView
//                     placeholder={'Card Holder Name'}
//                     value={holderName}
//                     onChangeText={onInputChange}
//                   />
//                   <TextView
//                     placeholder={'Card Number'}
//                     value={cardNumber}
//                     onChangeText={e => setCardNumber(e.replace(/[^0-9]/g, ''))}
//                     isNumeric={true}
//                     maxLength={16}
//                   />
//                   <View style={{ flexDirection: 'row', width: width / 2 }}>
//                     <TextInput
//                       placeholder={'Exp. Month'}
//                       value={month}
//                       onChangeText={e => setMonth(e.replace(/[^0-9]/g, ''))}
//                       keyboardType='numeric'
//                       maxLength={2}
//                       style={styles.input}
//                       placeholderTextColor={'gray'}


//                     />

//                     <TextInput
//                       placeholder={'Exp. Year'}
//                       value={year}
//                       onChangeText={e => setYear(e.replace(/[^0-9]/g, ''))}
//                       maxLength={4}
//                       style={styles.input}
//                       keyboardType='numeric'
//                       placeholderTextColor={'gray'}
//                     />
//                   </View>
//                   <TextView
//                     placeholder={'CVV'}
//                     value={cvv}
//                     onChangeText={e => setCvv(e.replace(/[^0-9]/g, ''))}
//                     isNumeric={true}
//                     maxLength={3}

//                   />
//                   <TouchableOpacity
//                     //  onPress={showAlert}
//                     onPress={() => bookNowHandler()}
//                     style={{
//                       marginTop: 10,
//                       backgroundColor: color.appOrangeColor,
//                       width: '90%',
//                       padding: 10,
//                       borderRadius: 10,
//                       justifyContent: 'center',
//                       height: 50,
//                       marginHorizontal: 20,
//                     }}>
//                     <View
//                       style={{
//                         position: 'absolute',
//                         flex: 1,
//                       }}>
//                       <Image
//                         source={Images.whiteDot}
//                         style={{
//                           paddingLeft: 70,
//                           width: 25,
//                           height: 25,
//                           resizeMode: 'contain',
//                         }}
//                       />
//                     </View>
//                     <Text
//                       style={{
//                         textAlign: 'center',
//                         fontSize: 15,
//                         color: color.appWhiteColor,
//                       }}>
//                       Book Now
//                     </Text>
//                   </TouchableOpacity>
//                   {
//                     loader ? <View style={{ top: -36, right: -65 }}><ActivityIndicator size={'small'} color='#fff' /></View> : null
//                   }
//                 </View>
//               </KeyboardAwareView>
//             ) : null}
//             {toggleCheckBox ? (
//               <View style={{ marginHorizontal: 20, marginTop: 20 }}>
//                 <Text style={{ color: '#000', fontSize: 20, fontWeight: '600' }}>
//                   If you choose this payment option, the accommodation will be
//                   pre-reserved for you. However, to confirm the booking you will
//                   have to make a bank transfer deposit to Shortlet Rentals bank
//                   account-Zenith Bank- Account number- 1214818652. Send payment slip
//                   via Whatsapp to +234 912 287 7657. Thank you.
//                 </Text>
//               </View>
//             ) : null}
//             {pay && (
//               <View style={{ flex: 1 }}>
//                 <Paystack
//                   paystackKey="pk_test_c7bf5a4f97f2ad9e0dc1d77fb6f92aa21769ece0"
//                   // paystackKey="pk_live_21147ca40d855a646b54e506fb8baa775d8fa131"
//                   amount={billingDetail.amount}
//                   billingEmail={billingDetail.billingEmail}
//                   billingMobile={billingDetail.billingMobile}
//                   activityIndicatorColor="green"

//                   onCancel={(e) => {
//                     // handle response here
//                     // Toast.show("Transaction Cancelled!!", {
//                     //   duration: Toast.durations.LONG,
//                     // });
//                     console.log('transaction Cancelled');
//                   }}
//                   onSuccess={(response) => {
//                     // handle response here

//                     const responseObject = response["transactionRef"]["message"];
//                     console.log('resop', response)
//                     if (responseObject === "Approved") {
//                       Toast.show({ type: 'success', text1: "Transaction Approved!!" }
//                         // , {
//                         //   // duration: Toast.durations.LONG,
//                         // }
//                       );
//                       setPay(false)
//                       props.navigation.navigate('OrderScreen',{data:response})
                     
//                     }
//                   }}
//                   autoStart={pay}
//                 />
//               </View>
//             )}

//           </ScrollView>
//       }
//     </View>
//   );
// };
// export default Payment;
// const styles = StyleSheet.create({
//   button: {
//     backgroundColor: '#4ba37b',
//     width: 100,
//     borderRadius: 50,
//     alignItems: 'center',
//     marginTop: 100,
//   },
//   input: {
//     padding: 15,
//     height: 50,
//     margin: 10,
//     backgroundColor: color.appTextBackgoundColor,
//     borderRadius: 10,
//     fontSize: 15,
//     marginHorizontal: 20,
//   },
// });

