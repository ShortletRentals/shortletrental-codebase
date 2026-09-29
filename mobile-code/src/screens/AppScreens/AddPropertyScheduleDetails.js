import { StyleSheet, Text, View, TouchableOpacity, Image, ScrollView, TextInput, ActivityIndicator } from 'react-native'
import React, { useContext, useEffect } from 'react'
import Header from '../../components/Header'
import { useState, useRef } from 'react'
import TextView from '../../components/TextView'
import { color } from '../../styles/colors'
import { width } from '../../styles/style'
import Images from '../../styles/Images'
import ActionSheet, { ActionSheetRef } from "react-native-actions-sheet";
import { useRoute } from '@react-navigation/native'
import { Dropdown } from 'react-native-element-dropdown'
import { Context as BookingContext } from '../../context/BookingContext'
import { getAllCountry, getAllProvince, Schedule_Event } from '../../network/Webconstant'
import axios from 'axios'
import { Toast } from 'react-native-toast-message/lib/src/Toast'
import { BackHandler } from 'react-native'
import { KeyboardAwareView } from 'react-native-keyboard-aware-view'

const data = [
  { label: 'Company', value: '1' },
  { label: 'Dr', value: '2' },
  { label: 'Family', value: '3' },
  { label: 'Mr & Mrs', value: '4' },
  { label: 'Mr.', value: '5' },
  { label: 'Mrs.', value: '5' },
  { label: 'Ms.', value: '5' },
  { label: 'PhD', value: '5' },
  { label: 'Prof', value: '5' },
];

const countryCode = [
  { label: '+91', value: '1' },
  { label: '+91', value: '2' },
  { label: '+91', value: '3' },
  { label: '+91', value: '4' },
  { label: '+91', value: '5' },
  { label: '+91', value: '6' },
  { label: '+91', value: '7' },
  { label: '+91', value: '8' },
  { label: '+91', value: '9' },
];

const DATA = [
  { label: 'Yes', value: '1' },
  { label: 'No', value: '2' },
]
const AddPropertyScheduleDetails = (props) => {
  const [firstName, setFirstName] = useState('')
  const [lastName, setLastName] = useState('')
  const [location, setLocation] = useState('')
  const [email, setEmail] = useState('')
  const [phoneNumber, setPhoneNumber] = useState('')
  const [message, setMessage] = useState('')
  const [textArea, setTextArea] = useState('')
  const actionSheetRef = useRef(null);
  const route = useRoute()
  const [Time, setTime] = useState(route?.params?.TIME)
  const [Date, setDate] = useState(route?.params?.DATE)
  const [value, setValue] = useState('');
  const [type, setType] = useState('')
  const [countryCodeNew, setcountryCode] = useState('');
  const [countryCodeContactPerson, setCountryCodeContactPerson] = useState('');
  const [province, setProvince] = useState('');
  const [contactPersonName, setContactPersonName] = useState('')
  const [contactPersonNumber, setContactPersonNumber] = useState('')
  const [provinceData, setProvinceData] = useState([])
  const [isSelect, setIsSelect] = useState(false)
  const [province_id, setProvince_id] = useState('')
  const [loader, setLoader] = useState(false)
  const [countryName, setCountryName] = useState([])
  const [countryData, setCountryData] = useState([])


  const {
    // getAllCountryApi,
    getAreaApi,
    updateBookingData,
    // getProvinceApi,
    getCityApi,
    getAllCountryCodeApi,
    state: { bookingData,
      //  provinceData,
      cityData, areaData,
      //  countryData
    },
  } = useContext(BookingContext);
  // console.log('schedu date', Time, Date,countryName);

  useEffect(() => {
    // getAllCountryCodeApi()
    getProvinceApi()
    // getAllCountryName()
  }, [])

  useEffect(() => {
    getAllCountryName()
  }, [])
  const getAllCountryName = async () => {
    try {
      axios({
        method: 'get',
        url: getAllCountry,

      }).then(
        function (response) {
          if (response.data.status === true) {
            

            let temp = response.data.data.map((item) => { return { value: item.id, label: item.sortname +" (+"+`${(item.phonecode)}`+")" } })
            setCountryName(temp)
            setCountryData(temp)
          
          }
        }
      )
    } catch (error) {
      console.log('eerrr', error)
    }
  }
  const getProvinceApi = async () => {
    try {
      axios({
        method: 'post',
        url: getAllProvince,
      }).then(
        function (response) {
          if (response.data.status === true) {
            // console.log('====res province data', response.data.data)
            let temp = response.data.data.map((item) => { return { value: item.id, label: item.name } })
            setProvinceData(temp)
            // dispatch({ type: 'updateProvince', payload: temp })
          }
        }
      )
    } catch (error) {
      console.log('eerrr', error)
    }
  }
  // console.log('typesssssss', countryName);
  const onInputChange = (value) => {
   
    // console.log('Input value: ', value); 
    const re = /^[a-zA-Z\s]*$/;
    if (value === "" || re.test(value)) {
      setContactPersonName(value);
    }
  }
  const onScheduleEventHandler = () => {
    if (value == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please select title'
      })
    } else if (firstName == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter first name'
      })

    } else if (lastName == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter last name'
      })
    }
    else if (email == '') {
      Toast.show({
        type: 'error',
        text1: 'Shortlet',
        text2: 'Please enter email Address'
      })
    } else if (email !== '') {
      let regex =
        /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
      if (regex.test(email) == false) {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Please enter valid email Address'
        })
      }


      else if (countryCodeNew == '') {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Please select country code'
        })
      } else if (phoneNumber == '') {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Please enter phone number'
        })
      } else if (phoneNumber.length < 8 || phoneNumber.length > 15) {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Please enter min 8 digits and max 15 digits phone number'
        })
      } else if (province == '') {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Please select province'
        })
      } else if (type == '') {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Please select onsite inspection'
        })
      } else if (location == '') {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Please enter address'
        })
      } else if (message == '') {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Please enter landmarks'
        })
      }

      else if (type == 'No') {
        if (contactPersonName == '') {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: 'Please enter contact person name'
          })

        } else if (countryCodeContactPerson == '') {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: 'Please select contact country code'
          })
        } else if (contactPersonNumber == '') {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: 'Please enter contact person name'
          })
        } else if (contactPersonNumber.length < 8 || contactPersonNumber.length > 15) {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: 'Please enter min 8 digits and max 15 digits contact person number'
          })
        } else if (isSelect === false) {
          Toast.show({
            type: 'error',
            text1: 'Shortlet',
            text2: 'Please select terms and conditions...'
          })
        }
        else {
          onClickSchduleEventApi()
        }
      } else if (isSelect === false) {
        Toast.show({
          type: 'error',
          text1: 'Shortlet',
          text2: 'Please select terms and conditions...'
        })
      }
      else {
        onClickSchduleEventApi()
      }
    }
  }

  // console.log('ererrerer', province_id);
  const onClickSchduleEventApi = () => {
    setLoader(true)
    axios({
      method: 'post',
      url: Schedule_Event,
      data: {
        title: value,
        full_name: firstName,
        surname: lastName,
        email: email,
        country_code: countryCodeNew,
        mobile: phoneNumber,
        appointment_date: Date,
        schedule_time: Time,
        province_id: province_id,
        onsite_inspection: type,
        landmark: message,
        address: location,
        contact_person_name: contactPersonName,
        contact_person_country_code: countryCodeContactPerson,
        contact_person_number: contactPersonNumber
      }
    }).then(
      function (response) {
        // console.log('response schedule event', response);
        if (response.data.status === true) {
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message
          })
          props?.navigation?.navigate('BeforHome')
          setLoader(false)
        } else {
          Toast.show({
            type: 'success',
            text1: 'Shortlet',
            text2: response.data.message
          })
          setLoader(false)
        }
      }
    ).catch((e) => {
      console.log('error is schedule event', e);
      setLoader(false)
    })
  }
  const handler = () => {
    props.navigation.goBack()
  }
  useEffect(() => {
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
  return (
    <View style={{ flex: 1, backgroundColor: '#FFFFFF' }}>
      <Header Heading={'Schedule Detail'} onPress={() => props.navigation.navigate('AddPropertyScheduleDate')} />
<KeyboardAwareView>
      <ScrollView >

        <Text style={{ fontSize: 20, fontWeight: '600', color: '#000', marginHorizontal: 20, marginTop: 12 }}>Enter Detail</Text>
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
          // placeholderStyle={styles.placeholderStyle}
          // selectedTextStyle={styles.selectedTextStyle}
          // inputSearchStyle={styles.inputSearchStyle}
          // iconStyle={styles.iconStyle}
          placeholderStyle={{color:'#000'}}
          itemTextStyle={{color:'#000'}}
          selectedTextProps={{style:{color:'#000'}}}
          data={data}
          search={false}
          maxHeight={300}
          labelField="label"
          valueField="value"
          placeholder="Select Title"
          // searchPlaceholder="5 guests"
          value={data}
          onChange={item => {
            setValue(item.label);
          }}
        />
        <TextView placeholder={'First Name *'} value={firstName} onChangeText={(text) => setFirstName(text)} />
        <TextView placeholder={'Last Name *'} value={lastName} onChangeText={(text) => setLastName(text)} />
        <TextInput
          style={{
            paddingLeft: 15,
            height: 50,
            margin: 10,
            backgroundColor: color.appTextBackgoundColor,
            borderRadius: 10,
            marginLeft: 20,
            marginRight: 20,
            fontSize: 15,
            color:'#000'
          }}
          placeholderTextColor={color.appTextColor}
          keyboardType='email-address'
          placeholder={'Email *'} value={email} onChangeText={(text) => setEmail(text.replace(/[^A-Za-z0-9-@A-Za-z0-9.A-Za-z0-9]/g,""))}
        />
        {/* <TextView placeholder={'Email *'} value={email} onChangeText={(text) => setEmail(text.replace(/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i))}  /> */}
        {/* <Text style={{ fontSize: 14, marginHorizontal: 20, fontWeight: 'bold', color: 'black', fontFamily: 'Medium' }}>Type your property address here. if you have multiple properties in different locations, you will need a separate appointment for each of those. *</Text> */}
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
          // placeholderStyle={styles.placeholderStyle}
          // selectedTextStyle={styles.selectedTextStyle}
          // inputSearchStyle={styles.inputSearchStyle}
          // iconStyle={styles.iconStyle}
          // data={countryData.country}
          placeholderStyle={{color:'#000'}}
          itemTextStyle={{color:'#000'}}
          selectedTextProps={{style:{color:'#000'}}}
          data={countryName}
          search={false}
          maxHeight={300}
          labelField="label"
          valueField="value"
          placeholder="Country Code"
          // searchPlaceholder="5 guests"
          value={countryName.label}
          onChange={item => {
            // console.log('itemis county', item),
              setcountryCode(item.label)
          }}
        />
        <TextView placeholder={'Phone number *'} value={phoneNumber} onChangeText={(text) => setPhoneNumber(text.replace(/[^0-9]/g, ''))} maxLength={15} isNumeric={true} />
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
          // placeholderStyle={styles.placeholderStyle}
          // selectedTextStyle={styles.selectedTextStyle}
          // inputSearchStyle={styles.inputSearchStyle}
          // iconStyle={styles.iconStyle}
          placeholderStyle={{color:'#000'}}
          itemTextStyle={{color:'#000'}}
          selectedTextProps={{style:{color:'#000'}}}
          data={provinceData}
          search={false}
          maxHeight={300}
          labelField="label"
          valueField="value"
          placeholder="Select Province"
          // searchPlaceholder="5 guests"
          value={provinceData.label}
          onChange={item => {
            setProvince(item.label);
            // provinceId = item.value;
            setProvince_id(item.value)
            // getCity();
          }}
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
            paddingRight: 15,
          }}
          // placeholderStyle={styles.placeholderStyle}
          // selectedTextStyle={styles.selectedTextStyle}
          // inputSearchStyle={styles.inputSearchStyle}
          // iconStyle={styles.iconStyle}
          placeholderStyle={{color:'#000'}}
          itemTextStyle={{color:'#000'}}
          selectedTextProps={{style:{color:'#000'}}}
          data={DATA}
          search={false}
          maxHeight={300}
          labelField="label"
          valueField="value"
          placeholder="Available for the onsite-inspection"
          // searchPlaceholder="5 guests"
          value={DATA}
          onChange={item => {
            setType(item.label);
          }}
        />
        <TextView placeholder={'Address *'} value={location} onChangeText={(text) => setLocation(text)} />
        {/* <Text style={{ fontSize: 14, marginHorizontal: 20, fontWeight: 'bold', color: 'black', fontFamily: 'Medium' }}>Please share anything that will help us locate your property on time, like landmarks. If your properties are within the same building/estate, kindly state it here. *</Text> */}

        {/* <TextView placeholder={'Message'} value={message} onChangeText={(text) => setMessage(text)} /> */}
        <TextInput
          numberOfLines={5}
          textAlignVertical='top'
          style={{
            paddingLeft: 15,
            height: 100,
            margin: 10,
            backgroundColor: color.appTextBackgoundColor,
            borderRadius: 10,
            // marginLeft: 20,
            // marginRight: 20,

            marginHorizontal: 20,
            fontSize: 15,
            color:'#000'
          }}
          placeholderTextColor={color.appTextColor}
          placeholder={'Please share anything that will help us locate your property on time,like landmarks *'}
          value={message}
          onChangeText={(text) => setMessage(text)}
          multiline
        />

        {
          type == 'No' ? <View>
            <TextInput 
            placeholder={'Contact Person Name *'} 
            placeholderTextColor={'#000'}
            value={contactPersonName} 
           
            onChangeText={onInputChange} 
            keyboardType='ascii-capable'
            style={{
              paddingLeft: 15,
              height: 50,
              margin: 10,
              backgroundColor: color.appTextBackgoundColor,
              borderRadius: 10,
              marginLeft: 20,
              marginRight: 20,
              fontSize: 15,
              color:'#000'
            }}
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
                paddingRight: 15,
              }}
              // placeholderStyle={styles.placeholderStyle}
              // selectedTextStyle={styles.selectedTextStyle}
              // inputSearchStyle={styles.inputSearchStyle}
              // iconStyle={styles.iconStyle}
              placeholderStyle={{color:'#000'}}
          itemTextStyle={{color:'#000'}}
          selectedTextProps={{style:{color:'#000'}}}
              data={countryData}
              search={false}
              maxHeight={300}
              labelField="label"
              valueField="value"
              placeholder="Country Code"
              // searchPlaceholder="5 guests"
              value={countryData.label}
              onChange={item => {
                setCountryCodeContactPerson(item.label);
              }}
            />
            <TextView placeholder={'Contact Person Number *'} value={contactPersonNumber} maxLength={15} onChangeText={(text) => setContactPersonNumber(text.replace(/[^0-9]/g, ''))} isNumeric={true} />
          </View> : null
        }

        <View style={{ flexDirection: 'row', marginHorizontal: 20,justifyContent:'center',alignItems:'center' }}>
          <TouchableOpacity onPress={() => setIsSelect(!isSelect)}>
            {
              isSelect ? <Image source={Images.checkfull} style={{ height: 24, width: 24 }} /> :
                <Image source={Images.checkblank} style={{ height: 24, width: 24 }} />}
          </TouchableOpacity>
          <Text style={{ marginHorizontal: 10 ,color:'#000'}}>I have read and I agree with the host policy</Text>
        </View>
        <TouchableOpacity onPress={() =>
          //  actionSheetRef.current?.show()
          onScheduleEventHandler()
        }
          style={{
            marginTop: 20,
            backgroundColor: color.appOrangeColor,
            width: '90%',
            padding: 10,
            borderRadius: 10,
            justifyContent: 'center',
            alignSelf: 'center',
            height: 50,
            marginBottom: 50
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

          <Text style={{ textAlign: "center", fontSize: 15, color: color.appWhiteColor }}>Schedule Event</Text>
        </TouchableOpacity>
        {
          loader ? <View style={{ top: -85, right: -75 }}><ActivityIndicator size='small' color={'#fff'} /></View> : null
        }
      </ScrollView>
      </KeyboardAwareView>
      <ActionSheet ref={actionSheetRef}>
        {/* <View style={{ height: 200 }} /> */}
        <View style={{ alignSelf: 'center', marginTop: 30 }} >
          <View style={{ justifyContent: 'center', alignItems: 'center', alignSelf: 'center' }}>
            <Image source={Images.checkIcon} style={{ height: 60, width: 60, borderRadius: 30 }} resizeMode='contain' />
          </View>
          <Text style={{ color: 'black', fontSize: 20, textAlign: 'center', fontWeight: 'bold', marginVertical: 8 }}>Confirmed</Text>
          <Text style={{ color: 'black', fontSize: 16, fontWeight: '600' }}>You are scheduled with Shortlet Rental</Text>
        </View>
        <TouchableOpacity onPress={() => props.navigation.navigate('BecomeaHostHome')} style={{
          marginTop: 20,
          backgroundColor: color.appBlueColor,
          width: '90%',
          padding: 10,
          borderRadius: 10,
          justifyContent: 'center',
          alignSelf: 'center',
          height: 50,
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

          <Text style={{ textAlign: "center", fontSize: 15, color: color.appWhiteColor }}>Schedule Event</Text>
        </TouchableOpacity>
      </ActionSheet>
    </View>
  )
}

export default AddPropertyScheduleDetails

const styles = StyleSheet.create({})