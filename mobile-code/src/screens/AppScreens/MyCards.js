// import React, { useState, useRef, useEffect, useContext } from "react";
// import { View, Switch, Image, Text, TextInput, ImageBackground, StyleSheet, TouchableOpacity, _Text, ScrollView, FlatList, ActivityIndicator } from "react-native";
// import { color, height, width } from "../../styles/colors";
// import Images from "../../styles/Images";
// import { commonStyles } from "./../../styles/style";
// import { Colors, colors } from "react-native/Libraries/NewAppScreen";
// import Header from "../../components/Header";
// import TextView from "../../components/TextView";
// import CheckBox from '@react-native-community/checkbox';
// import { SafeAreaView } from "react-native-safe-area-context";
// import ActionSheet, { ActionSheetRef } from "react-native-actions-sheet";
// import axios from "axios";
// import { AddToCart, Default_Card, Delete_Card, get_store_card, Update_Card } from "../../network/Webconstant";
// import AsyncStorage from "@react-native-async-storage/async-storage";
// import Carousel, { Pagination } from "react-native-snap-carousel";
// import { useIsFocused } from "@react-navigation/native";
// import { createShimmerPlaceholder } from 'react-native-shimmer-placeholder'
// import LinearGradient from "react-native-linear-gradient";
// import { Context as CardContext } from '../../context/CardContext'
// import { Context as AuthContext } from '../../context/AuthContext'
// import { SliderShimmer, SliderShimmerMyCard } from "../../components/Skeleton";
// import { Toast } from "react-native-toast-message/lib/src/Toast";

// const MyCards = ({ props, navigation }) => {
//     const actionSheetRef = useRef(null);
//     const actionSheetRef1 = useRef(null)

//     const [isEnabled, setIsEnabled] = useState(false);
//     // const [number, onChangeNumber] = React.useState(null);
//     const [cardNumber1, setCardNumber1] = useState('')
//     const [cardNumber2, setCardNumber2] = useState('')
//     const [cardNumber3, setCardNumber3] = useState('')
//     const [cardNumber4, setCardNumber4] = useState('')
//     const [expDate, setExpDate] = useState('')
//     const [expMonth, setExpMonth] = useState('')
//     const [expYear, setExpYear] = useState('')
//     const [cvv, setCvv] = useState('')
//     const [cardHolderName, setCardHolderName] = useState('')
//     const [token_id, setToken_Id] = useState('')
//     const [fillData, setFillData] = useState([])
//     const [UserID, setUserID] = useState('')
//     const [cardId, setCardId] = useState('')
//     const [activeIndex, setActivityIndex] = useState(0)
//     const [isLoading, setIsLoading] = useState(true)
//     const [loader, setLoader] = useState(false)


//     useEffect(() => {
//         User_Id()
//         setLoader(true)
//         GetToCardApi()
//         setLoader(false)

//     }, [])
//     const { checkActiveUser, state: { Token_ID } } = useContext(AuthContext)
//     const { addNewCardApi, getNewCardApi, defaultCardApi, editCardApi, deleteCardApi, state: { activityIndicator, cardData } } = useContext(CardContext)

//     const addToCart = () => {
//         if (cardHolderName.trim() == '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter card holder name'
//             })
//         } else if (cardNumber1.trim() === '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter card number'
//             })
//         }
//         else if (cardNumber2.trim() === '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter card number'
//             })
//         } else if (cardNumber3.trim() === '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter card number'
//             })
//         } else if (cardNumber4.trim() === '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter card number'
//             })
//         } else if (expMonth.trim() == '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter month'
//             })
//         } else if (expMonth > 12) {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter valid month'
//             })
//         }
//         else if (expYear.trim() == '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter year'
//             })
//         } else if (expYear > 2035 || expYear < 2010) {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter valid year'
//             })
//         }
//         else if (cvv.trim() == '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter cvv'
//             })
//         } else {
//             onSubmitSaveButton()

//         }
//     }



//     const onSubmitSaveButton = () => {
//         console.log('handle add to card', Token_ID)
//         let data = { card_holder_name: cardHolderName, card_number: cardNumber1 + cardNumber2 + cardNumber3 + cardNumber4, month: expMonth, year: expYear, cvv: cvv }
//         let headers = { Authorization: `Bearer ${Token_ID}` }

//         addNewCardApi(data, headers, () => {
//             actionSheetRef.current?.hide()
//             setLoader(false)
//             GetToCardApi()
//             setCardHolderName('')
//             setCardNumber2('')
//             setCardNumber1('')
//             setCardNumber3('')
//             setCardNumber4('')
//             setExpMonth('')
//             setExpYear('')
//             setCvv('')
//         })

//     }
//     const User_Id = async () => {
//         let User_ID = await AsyncStorage.getItem('USER_ID')
//         console.log('user-idddd', UserID)
//         setUserID(User_ID)
//     }

//     // const toggleSwitch = () => setIsEnabled(previousState => !previousState);

//     const toggleSwitch = async () => {
//         let data = { card_id: cardId }
//         let headers = { Authorization: `Bearer ${Token_ID}` }
//         defaultCardApi(data, headers, () => {
//             setIsEnabled(previousState => !previousState)
//             GetToCardApi()
//         })
//         // axios({
//         //     method: 'post',
//         //     url: Default_Card,
//         //     data: { card_id: cardId },
//         //     headers: { Authorization: `Bearer ${token_id}` }
//         // }).then(
//         //     function (response) {
//         //         console.log('default api card', response.data)
//         //         if (response.data.status === true) {
//         //             alert(response.data.message)
//         //             setIsEnabled(previousState => !previousState)
//         //             GetToCardApi()
//         //             // let tempData = homeData
//         //             // for (let i = 0; i < tempData.length; i++) {
//         //             //     const element = tempData[i];
//         //             //     console.log('element is', element.defalult_card)
//         //             //     element.defalult_card = cardId
//         //             // }
//         //             // setFillData(tempData)
//         //         } else {
//         //             alert(response.data.message)
//         //         }
//         //     }
//         // )
//     }
//     // let controller = new AbortController();
//     // console.log('====card', cardData)
//     const GetToCardApi = () => {

//         let data = { propertyid: UserID }
//         let headers = { Authorization: `Bearer ${Token_ID}` }
//         getNewCardApi(data, headers, () => {

//         })


//     }

//     const ShimmerPlaceholder = createShimmerPlaceholder(LinearGradient)

//     // console.log('fillll', fillData)
//     const renderItem = ({ item, index }) => {
//         return (
//             <>
//                 {
//                     activityIndicator ? (<SliderShimmerMyCard />)
//                         :
//                         <ImageBackground source={Images.CardDesignIcon} style={{ width: width - 30, alignSelf: 'center', height: width * (200 / 375), marginHorizontal: 20, marginTop: 10 }}>
//                             <View style={{ marginTop: 10, marginLeft: 10, marginRight: 10 }}>
//                                 <Text style={{ color: color.appWhiteColor, fontSize: 20 }}>MASTERCARD</Text>
//                                 <View style={{ flexDirection: 'row', marginTop: 15 }}>
//                                     <Text style={styles.container}>{item.card_number}</Text>
//                                 </View>
//                                 <View style={{ flexDirection: 'row', marginTop: 10 }}>
//                                     <View >
//                                         <Text style={styles.container}>Valid Thru</Text>
//                                         <Text style={styles.container}>{item.month}/{item.year}</Text>
//                                     </View>
//                                     <View style={{ height: width * (40 / 375), backgroundColor: color.appWhiteColor, justifyContent: 'center', borderRadius: 10, marginHorizontal: 10, marginTop: 8 }}>
//                                         <Text style={{ marginHorizontal: 20, fontSize: 20, color: color.appOrangeColor, fontWeight: '500' }}>{item.cvv}</Text>
//                                     </View>
//                                 </View>
//                                 <View style={{ justifyContent: 'space-between', flexDirection: 'row', marginTop: 10 }}>
//                                     <Text style={styles.container}>{item.card_holder_name}</Text>
//                                     <Image source={Images.cardTypeIcon} />
//                                 </View>
//                             </View>
//                         </ImageBackground>

//                 }
//             </>
//         )
//     }

//     const pagination = () => {
//         return (
//             <Pagination
//                 dotsLength={cardData.length}
//                 activeDotIndex={activeIndex}
//                 containerStyle={{ marginBottom: 10 }}

//                 dotStyle={{
//                     width: 16,
//                     height: 16,
//                     borderRadius: 10,
//                     //   marginHorizontal: 8,
//                     // backgroundColor: '#3C317D',

//                 }}
//                 inactiveDotStyle={{
//                     // Define styles for inactive dots here
//                     backgroundColor: '#F99428'
//                 }}
//                 inactiveDotOpacity={0.5}
//                 inactiveDotScale={0.6}
//                 inactiveDotColor='#F99428'
//                 dotColor="#3C317D"

//             />
//         );
//     }

//     const editCardValidation = () => {
//         if (cardHolderName.trim() == '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter card holder name'
//             })
//         } else if (cardNumber1.trim() === '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter card number'
//             })
//         }
//         else if (cardNumber2.trim() === '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter card number'
//             })
//         } else if (cardNumber3.trim() === '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter card number'
//             })
//         } else if (cardNumber4.trim() === '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter card number'
//             })
//         } else if (expMonth.trim() == '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter month'
//             })
//         } else if (expMonth > 12) {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter valid month'
//             })
//         }
//         else if (expYear.trim() == '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter year'
//             })
//         } else if (expYear > 2035 || expYear < 2010) {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter valid year'
//             })
//         }
//         else if (cvv.trim() == '') {
//             Toast.show({
//                 type: 'error',
//                 text1: 'Shortlet',
//                 text2: 'please enter cvv'
//             })
//         } else {
//             EditCardApi()
//         }
//     }
//     const EditCardApi =async () => {
//         const Localtoken = await AsyncStorage.getItem('token_id');
//         let data = { card_holder_name: cardHolderName, card_number: cardNumber1 + cardNumber2 + cardNumber3 + cardNumber4, month: expMonth, year: expYear, cvv: cvv, card_id: cardId }
//         let headers = { Authorization: `Bearer ${Localtoken}` }
//         editCardApi(data, headers, () => {
//             actionSheetRef1.current?.hide()
//             setCardHolderName('')
//             setCardNumber1('')
//             setCardNumber2('')
//             setCardNumber3('')
//             setCardNumber4('')
//             setCvv('')
//             setExpMonth('')
//             setExpYear('')
//         })
//         // axios({
//         //     method: 'post',
//         //     url: Update_Card,
//         //     data: { card_holder_name: cardHolderName, card_number: cardNumber1 + cardNumber2 + cardNumber3 + cardNumber4, month: expMonth, year: expYear, cvv: cvv, card_id: cardId },
//         //     headers: { Authorization: `Bearer ${token_id}` }
//         // }).then(
//         //     function (response) {
//         //         console.log('update card resssss', response.data)
//         //         if (response.data.status == true) {
//         //             alert(response.data.message)
//         //             actionSheetRef1.current?.hide()
//         //             GetToCardApi()
//         //             setCardHolderName('')
//         //             setCardNumber1('')
//         //             setCardNumber2('')
//         //             setCardNumber3('')
//         //             setCardNumber4('')
//         //             setCvv('')
//         //             setExpMonth('')
//         //             setExpYear('')
//         //         } else {
//         //             alert(response.data.message.card_holder_name || response.data.message.card_number || response.data.message.cvv || response.data.message.month || response.data.message.year || response.data.message)
//         //         }
//         //     }
//         // ).catch((e) => {
//         //     console.log('error is ', e);
//         // })
//     }

//     const onSubmitDeleteCardApi = () => {
//         let data = { card_id: cardId }
//         let headers = { Authorization: `Bearer ${Token_ID}` }

//         deleteCardApi(data, headers, () => {
//             GetToCardApi()
//         })

//     }


//     return (
//         <>

//             <View style={commonStyles.container}>

//                 <Header props={props} Heading={'My Cards'} onPress={() => navigation.navigate('Account')} />
//                 <ScrollView contentContainerStyle={{ flexGrow: 1, marginTop: 10 }}>
//                     {
//                         cardData.length === 0 ?  <Text style={{ fontSize: 24, alignSelf: 'center',backgroundColor:color.appYellowColor,height:200,width:'90%',borderRadius:20,textAlign:'center',alignItems:'stretch',marginTop:10 ,color:'#fff'}}>No Card Here!</Text>
//                             :
//                             <Carousel
//                                 // ref={(c) => { this._carousel = c; }}
//                                 pinchGestureEnabled={false}
//                                 enableMomentum={true}
//                                 data={cardData}
//                                 renderItem={renderItem}
//                                 sliderWidth={width}
//                                 itemWidth={width}
//                                 layoutCardOffset={6}
//                                 // sliderHeight={200}
//                                 // ref={carouselRef}
//                                 bounces={false}
//                                 layout={"default"}
//                                 // autoplay={true}
//                                 // loop={true}
//                                 enableSnap={true}
//                                 // loopClonesPerSide={5}
//                                 inactiveSlideOpacity={1}
//                                 inactiveSlideScale={1}
//                                 onSnapToItem={(e) => {
//                                     // setCardId(fillData[e].id)
//                                     setCardId(cardData[e].id)
//                                     setActivityIndex(e),
//                                         // setIsEnabled(e === fillData[e].defalult_card)
//                                         setIsEnabled(e == cardData[e].defalult_card)
//                                     console.log('eeee', e, cardData[e].defalult_card)
//                                     console.log('====cardData', cardData)
//                                     // let tempData = fillData
//                                     // for (let i = 0; i < tempData.length; i++) {
//                                     //     const element = tempData[i];
//                                     //     console.log('element is',element.defalult_card)
//                                     //     if(element.defalult_card === cardId ){
//                                     //         element.defalult_card = !element.defalult_card
//                                     //     }
//                                     // }
//                                     // setFillData(tempData)

//                                 }}

//                             />
//                     }
//                     {cardData.length > 0 && pagination()}

//                     <TouchableOpacity style={{ borderRadius: 10, marginTop: 10, height: 60, marginHorizontal: 20, padding: 16, backgroundColor: color.appLightOrangeColor, borderColor: color.appOrangeColor, borderWidth: 2, flexDirection: "row", justifyContent: "space-between", alignItems: 'center' }}>
//                         <Text style={{ color: color.appOrangeColor, fontSize: 14 }}>Make Default</Text>
//                         {/* <Image source={Images.ArrowIcons} /> */}
//                         <View style={{ height: 28, backgroundColor: isEnabled ? 'orange' : '#767577', width: 50, borderRadius: 20, justifyContent: 'center' }}>
//                             <Switch
//                                 trackColor={{ false: "#767577", true: 'orange' }}
//                                 thumbColor={isEnabled ? "#fff" : "#f5dd4b"}
//                                 ios_backgroundColor="#3e3e3e"
//                                 onValueChange={() => toggleSwitch()}
//                                 value={isEnabled}
//                             />
//                         </View>
//                     </TouchableOpacity>

//                     <View style={{ flexDirection: 'row', marginHorizontal: 20, justifyContent: 'space-between' }}>

//                         <TouchableOpacity onPress={() => actionSheetRef1.current?.show()} style={{ borderRadius: 10, marginTop: 30, height: 60, backgroundColor: color.appLightOrangeColor, borderColor: color.appOrangeColor, borderWidth: 2, flexDirection: "row", justifyContent: "space-between", alignItems: 'center' }} >
//                             <Image source={Images.EditCardIcon} style={{ marginLeft: width * (20 / 375) }} />
//                             <Text style={{ color: color.appOrangeColor, fontSize: 14, marginHorizontal: width * (20 / 375) }}>Edit Card</Text>


//                         </TouchableOpacity>

//                         <TouchableOpacity onPress={() => onSubmitDeleteCardApi()} style={{ borderRadius: 10, marginTop: 30, height: 60, backgroundColor: color.appLightOrangeColor, borderColor: color.appOrangeColor, borderWidth: 2, flexDirection: "row", justifyContent: "space-between", alignItems: 'center' }}>
//                             <Image source={Images.deleteIcon} style={{ marginLeft: width * (20 / 375) }} />
//                             <Text style={{ color: color.appOrangeColor, fontSize: 14, marginHorizontal: width * (20 / 375) }}>Delete</Text>
//                         </TouchableOpacity>

//                     </View>
//                     <View style={{ justifyContent: "flex-end", bottom: 30, flex: 1, alignItems: 'center' }}>
//                         <TouchableOpacity onPress={() => actionSheetRef.current?.show()} style={{
//                             marginTop: 40,
//                             backgroundColor: color.appBlueColor,
//                             width: '90%',
//                             padding: 10,
//                             borderRadius: 10,
//                             justifyContent: 'center',
//                             alignSelf: 'center',
//                             marginBottom: width * (20 / 375),
//                             height: 50,
//                             marginHorizontal: 20
//                         }}>

//                             <View style={{
//                                 position: 'absolute',
//                                 flex: 1,
//                             }}>
//                                 <Image source={Images.whiteDot} style={{
//                                     paddingLeft: 70,
//                                     width: 25, height: 25, resizeMode: 'contain',
//                                 }} />
//                             </View>

//                             <Text style={{ textAlign: "center", fontSize: 15, color: color.appWhiteColor }}>Add New Card</Text>

//                         </TouchableOpacity>
//                     </View>

//                     <ActionSheet ref={actionSheetRef} >


//                         <View style={{ marginTop: 8 }} >
//                             <View style={{ paddingVertical: 12, marginHorizontal: 20 }}>
//                                 <Text style={{ fontSize: 20, fontWeight: '500', marginBottom: 10, color: color.primaryColorBlack }}>
//                                     Card Detail
//                                 </Text>
//                             </View>
//                             <View style={{ borderWidth: 0.5, borderColor: '#e3e3e3' }} />
//                             <View style={{ marginHorizontal: 20 }}>
//                                 <Text style={{ color: color.primaryColorBlack, fontWeight: '600', fontSize: 15, marginTop: 14 }}>Card Holder Name</Text>
//                                 <TextInput
//                                     placeholder="Enter your name"
//                                     style={{
//                                         height: 50,
//                                         marginHorizontal: 0,
//                                         // padding: 20,
//                                         paddingHorizontal: 12,
//                                         backgroundColor: color.appTextBackgoundColor,
//                                         borderRadius: 4,
//                                         width: '100%',
//                                         marginTop: 5
//                                     }}
//                                     value={cardHolderName}
//                                     onChangeText={(text) => setCardHolderName(text)}
//                                 />
//                             </View>
//                             <Text style={{ fontSize: 15, fontWeight: '500', marginBottom: 10, color: color.primaryColorBlack, marginTop: 14, marginHorizontal: 20 }}>
//                                 Card Number
//                             </Text>
//                         </View>
//                         <View style={{ justifyContent: 'space-around', flexDirection: 'row', marginHorizontal: 8 }}>
//                             <View >
//                                 <TextInput
//                                     style={styles.input}
//                                     onChangeText={(text) => setCardNumber1(text)}
//                                     value={cardNumber1}
//                                     placeholder="XXXX"
//                                     keyboardType='numeric'
//                                     maxLength={4}
//                                 />
//                             </View>
//                             <View >
//                                 <TextInput
//                                     style={styles.input}
//                                     // onChangeText={onChangeNumber}
//                                     value={cardNumber2}
//                                     onChangeText={(text) => setCardNumber2(text)}
//                                     placeholder="XXXX"
//                                     keyboardType='numeric'
//                                     maxLength={4}
//                                 />
//                             </View>
//                             <View >
//                                 <TextInput
//                                     style={styles.input}
//                                     // onChangeText={onChangeNumber}
//                                     onChangeText={(text) => setCardNumber3(text)}
//                                     value={cardNumber3}
//                                     placeholder="XXXX"
//                                     keyboardType='numeric'
//                                     maxLength={4}
//                                 />
//                             </View>
//                             <View >
//                                 <TextInput
//                                     style={styles.input}
//                                     // onChangeText={onChangeNumber}
//                                     onChangeText={(text) => setCardNumber4(text)}
//                                     value={cardNumber4}
//                                     placeholder="XXXX"
//                                     keyboardType='numeric'
//                                     maxLength={4}
//                                 />
//                             </View>


//                         </View>

//                         <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginHorizontal: 20, marginTop: 10 }}>
//                             <View >
//                                 <Text style={{ color: color.primaryColorBlack, marginBottom: 10 }}>Expiry Date</Text>
//                                 <TextInput
//                                     style={[styles.input, { width: 110 }]}
//                                     // onChangeText={onChangeNumber}
//                                     onChangeText={(text) => setExpMonth(text)}
//                                     value={expMonth}
//                                     placeholder="Month"
//                                     keyboardType='default'
//                                     maxLength={2}
//                                 />
//                             </View>
//                             <View style={{ alignSelf: 'flex-start' }}>
//                                 <TextInput
//                                     style={{
//                                         width: 110, marginTop: 30, height: 40,
//                                         // margin: 12,
//                                         // padding: 20,
//                                         paddingHorizontal: 12,
//                                         backgroundColor: color.appTextBackgoundColor,
//                                         borderRadius: 4,
//                                         marginHorizontal: 5
//                                     }}
//                                     // onChangeText={onChangeNumber}
//                                     onChangeText={(text) => setExpYear(text)}
//                                     value={expYear}
//                                     placeholder="Year"
//                                     keyboardType='default'
//                                     maxLength={4}
//                                 />
//                             </View>
//                             <View style={{}} >
//                                 <Text style={{ color: color.primaryColorBlack, marginBottom: 10 }}>CVV</Text>
//                                 <TextInput
//                                     style={[styles.input, { width: 100 }]}
//                                     // onChangeText={onChangeNumber}
//                                     onChangeText={(text) => setCvv(text)}
//                                     value={cvv}
//                                     placeholder="XXX"
//                                     keyboardType='default'
//                                     maxLength={3}
//                                 />
//                             </View>
//                         </View>

//                         <TouchableOpacity
//                             //  onPress={() => props.navigation.navigate('Logins')}
//                             onPress={() =>
//                                 addToCart()}
//                             style={{
//                                 marginTop: 20,
//                                 backgroundColor: color.appBlueColor,
//                                 width: '90%',
//                                 padding: 10,
//                                 borderRadius: 10,
//                                 justifyContent: 'center',
//                                 alignSelf: 'center',
//                                 marginBottom: 30,
//                                 height: 50
//                             }}>
//                             <View style={{
//                                 position: 'absolute',
//                                 flex: 1,
//                             }}>
//                                 <Image source={Images.whiteDot} style={{
//                                     paddingLeft: 70,
//                                     width: 25, height: 25, resizeMode: 'contain',
//                                 }} />
//                             </View>

//                             <Text style={{ textAlign: "center", fontSize: 15, color: color.appWhiteColor }}>Save</Text>
//                         </TouchableOpacity>

//                     </ActionSheet>
//                     <ActionSheet ref={actionSheetRef1} >
//                         <View style={{ marginTop: 8 }} >
//                             <View style={{ paddingVertical: 12, marginHorizontal: 20 }}>
//                                 <Text style={{ fontSize: 20, fontWeight: '500', marginBottom: 10, color: color.primaryColorBlack }}>
//                                     Card Detail
//                                 </Text>
//                             </View>
//                             <View style={{ borderWidth: 0.5, borderColor: '#e3e3e3' }} />
//                             <View style={{ marginHorizontal: 20 }}>
//                                 <Text style={{ color: color.primaryColorBlack, fontWeight: '600', fontSize: 15, marginTop: 10 }}>Card Holder Name</Text>
//                                 <TextInput
//                                     placeholder="Enter your name"
//                                     style={{
//                                         height: 50,
//                                         marginHorizontal: 0,
//                                         // padding: 20,
//                                         paddingHorizontal: 12,
//                                         backgroundColor: color.appTextBackgoundColor,
//                                         borderRadius: 4,
//                                         width: '100%',
//                                         marginTop: 5
//                                     }}
//                                     value={cardData.card_holder_name}
//                                     onChangeText={(text) => setCardHolderName(text)}
//                                 />
//                             </View>
//                             <Text style={{ fontSize: 15, fontWeight: '500', marginBottom: 10, color: color.primaryColorBlack, marginTop: 10, marginHorizontal: 20 }}>
//                                 Card Number
//                             </Text>
//                         </View>
//                         <View style={{ justifyContent: 'space-around', flexDirection: 'row', marginHorizontal: 8 }}>
//                             <View >
//                                 <TextInput
//                                     style={styles.input}
//                                     onChangeText={(text) => setCardNumber1(text)}
//                                     value={cardNumber1}
//                                     placeholder="XXXX"
//                                     keyboardType='numeric'
//                                     maxLength={4}
//                                     multiline
//                                 // onSubmitEditing={() => alert(`Welcome to ${cardNumber1}`)}
//                                 />
//                             </View>
//                             <View >
//                                 <TextInput
//                                     style={styles.input}
//                                     // onChangeText={onChangeNumber}
//                                     value={cardNumber2}
//                                     onChangeText={(text) => setCardNumber2(text)}
//                                     placeholder="XXXX"
//                                     keyboardType='numeric'
//                                     maxLength={4}
//                                 />
//                             </View>
//                             <View >
//                                 <TextInput
//                                     style={styles.input}
//                                     // onChangeText={onChangeNumber}
//                                     onChangeText={(text) => setCardNumber3(text)}
//                                     value={cardNumber3}
//                                     placeholder="XXXX"
//                                     keyboardType='numeric'
//                                     maxLength={4}
//                                 />
//                             </View>
//                             <View >
//                                 <TextInput
//                                     style={styles.input}
//                                     // onChangeText={onChangeNumber}
//                                     onChangeText={(text) => setCardNumber4(text)}
//                                     value={cardNumber4}
//                                     placeholder="XXXX"
//                                     keyboardType='numeric'
//                                     maxLength={4}

//                                 />
//                             </View>


//                         </View>

//                         <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginHorizontal: 20, marginTop: 10 }}>
//                             <View >
//                                 <Text style={{ color: color.primaryColorBlack, marginBottom: 10 }}>Expiry Date</Text>
//                                 <TextInput
//                                     style={[styles.input, { width: 110 }]}
//                                     // onChangeText={onChangeNumber}
//                                     onChangeText={(text) => setExpMonth(text)}
//                                     value={expMonth}
//                                     placeholder="Month"
//                                     keyboardType='default'
//                                     maxLength={2}
//                                 />
//                             </View>
//                             <View style={{ alignSelf: 'flex-start' }}>
//                                 <TextInput
//                                     style={{
//                                         width: 110, marginTop: 30, height: 40,
//                                         // margin: 12,
//                                         // padding: 20,
//                                         paddingHorizontal: 12,
//                                         backgroundColor: color.appTextBackgoundColor,
//                                         borderRadius: 4,
//                                         marginHorizontal: 5
//                                     }}
//                                     // onChangeText={onChangeNumber}
//                                     onChangeText={(text) => setExpYear(text)}
//                                     value={expYear}
//                                     placeholder="Year"
//                                     keyboardType='default'
//                                     maxLength={4}
//                                 />
//                             </View>
//                             <View style={{}} >
//                                 <Text style={{ color: color.primaryColorBlack, marginBottom: 10 }}>CVV</Text>
//                                 <TextInput
//                                     style={[styles.input, { width: 100 }]}
//                                     // onChangeText={onChangeNumber}
//                                     onChangeText={(text) => setCvv(text)}
//                                     value={cvv}
//                                     placeholder="XXX"
//                                     keyboardType='default'
//                                     maxLength={3}
//                                 />
//                             </View>
//                         </View>

//                         <TouchableOpacity
//                             //  onPress={() => props.navigation.navigate('Logins')}
//                             onPress={() => editCardValidation()}
//                             style={{
//                                 marginTop: 20,
//                                 backgroundColor: color.appBlueColor,
//                                 width: '90%',
//                                 padding: 10,
//                                 borderRadius: 10,
//                                 justifyContent: 'center',
//                                 alignSelf: 'center',
//                                 marginBottom: 30,
//                                 height: 50
//                             }}>
//                             <View style={{
//                                 position: 'absolute',
//                                 flex: 1,
//                             }}>
//                                 <Image source={Images.whiteDot} style={{
//                                     paddingLeft: 70,
//                                     width: 25, height: 25, resizeMode: 'contain',
//                                 }} />
//                             </View>

//                             <Text style={{ textAlign: "center", fontSize: 15, color: color.appWhiteColor }}>Save</Text>
//                         </TouchableOpacity>

//                         {/* <View style={{ marginTop: 8, marginHorizontal: 15, }} >
//                                 <View style={{ borderBottomColor: color.appTextBackgoundColor, borderBottomWidth: 1, marginBottom: 20 }}>
//                                     <Text style={{ fontSize: 15, fontWeight: '500', marginBottom: 10, color: color.primaryColorBlack }}>
//                                         Card Detail
//                                     </Text>
//                                 </View>
//                                 <View>
//                                     <Text style={{ color: color.primaryColorBlack, fontWeight: '600', fontSize: 15 }}>Card Holder Name</Text>
//                                     <TextInput
//                                         placeholder="Enter your name"
//                                         style={{
//                                             height: 50,
//                                             marginHorizontal: 0,
//                                             // padding: 20,
//                                             paddingHorizontal: 12,
//                                             backgroundColor: color.appTextBackgoundColor,
//                                             borderRadius: 4,
//                                             width: '100%'
//                                         }}
//                                         value={cardHolderName}
//                                         onChangeText={(text) => setCardHolderName(text)}
//                                     />
//                                 </View>
//                                 <Text style={{ fontSize: 15, fontWeight: '500', marginBottom: 10, color: color.primaryColorBlack, marginTop: 10 }}>
//                                     Card Number
//                                 </Text>
//                             </View>
//                             <View style={{ justifyContent: 'space-around', flexDirection: 'row' }}>
//                                 <View >
//                                     <TextInput
//                                         style={styles.input}
//                                         onChangeText={(text) => setCardNumber1(text)}
//                                         value={cardNumber1}
//                                         placeholder="XXXX"
//                                         keyboardType='numeric'
//                                         maxLength={4}
//                                     />
//                                 </View>
//                                 <View >
//                                     <TextInput
//                                         style={styles.input}
//                                         // onChangeText={onChangeNumber}
//                                         value={cardNumber2}
//                                         onChangeText={(text) => setCardNumber2(text)}
//                                         placeholder="XXXX"
//                                         keyboardType='numeric'
//                                         maxLength={4}
//                                     />
//                                 </View>
//                                 <View >
//                                     <TextInput
//                                         style={styles.input}
//                                         // onChangeText={onChangeNumber}
//                                         onChangeText={(text) => setCardNumber3(text)}
//                                         value={cardNumber3}
//                                         placeholder="XXXX"
//                                         keyboardType='numeric'
//                                         maxLength={4}
//                                     />
//                                 </View>
//                                 <View >
//                                     <TextInput
//                                         style={styles.input}
//                                         // onChangeText={onChangeNumber}
//                                         onChangeText={(text) => setCardNumber4(text)}
//                                         value={cardNumber4}
//                                         placeholder="XXXX"
//                                         keyboardType='numeric'
//                                         maxLength={4}
//                                     />
//                                 </View>


//                             </View>

//                             <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' }}>
//                                 <View >
//                                     <Text style={{ marginLeft: 20, color: color.primaryColorBlack }}>Expiry Date</Text>
//                                     <TextInput
//                                         style={[styles.input, { width: 80 }]}
//                                         // onChangeText={onChangeNumber}
//                                         onChangeText={(text) => setExpMonth(text)}
//                                         value={expMonth}
//                                         placeholder="Month"
//                                         keyboardType='default'
//                                         maxLength={2}
//                                     />
//                                 </View>
//                                 <View style={{ alignSelf: 'flex-start' }}>
//                                     <TextInput
//                                         style={{
//                                             width: 80, marginTop: 32, height: 40,
//                                             margin: 12,
//                                             // padding: 20,
//                                             paddingHorizontal: 12,
//                                             backgroundColor: color.appTextBackgoundColor,
//                                             borderRadius: 4,
//                                             marginHorizontal: 20
//                                         }}
//                                         // onChangeText={onChangeNumber}
//                                         onChangeText={(text) => setExpYear(text)}
//                                         value={expYear}
//                                         placeholder="Year"
//                                         keyboardType='default'
//                                         maxLength={4}
//                                     />
//                                 </View>
//                                 <View style={{ paddingRight: 20 }} >
//                                     <Text style={{ marginLeft: 20, color: color.primaryColorBlack }}>CVV</Text>
//                                     <TextInput
//                                         style={styles.input}
//                                         // onChangeText={onChangeNumber}
//                                         onChangeText={(text) => setCvv(text)}
//                                         value={cvv}
//                                         placeholder="XXX"
//                                         keyboardType='default'
//                                         maxLength={3}
//                                     />
//                                 </View>
//                             </View>

//                             <TouchableOpacity
//                                 //  onPress={() => props.navigation.navigate('Logins')}
//                                 onPress={() => EditCardApi()}
//                                 style={{
//                                     marginTop: 20,
//                                     backgroundColor: color.appBlueColor,
//                                     width: width - 30,
//                                     padding: 10,
//                                     borderRadius: 10,
//                                     justifyContent: 'center',
//                                     alignSelf: 'center',
//                                     marginBottom: 30,
//                                     height: 50
//                                 }}>
//                                 <View style={{
//                                     position: 'absolute',
//                                     flex: 1,
//                                 }}>
//                                     <Image source={Images.whiteDot} style={{
//                                         paddingLeft: 70,
//                                         width: 25, height: 25, resizeMode: 'contain',
//                                     }} />
//                                 </View>

//                                 <Text style={{ textAlign: "center", fontSize: 15, color: color.appWhiteColor }}>Save</Text>
//                             </TouchableOpacity> */}

//                     </ActionSheet>
//                 </ScrollView>
//             </View>
//             {/* } */}
//         </>

//     )
// }

// const styles = StyleSheet.create({
//     input: {
//         height: 40,
//         // margin: 12,
//         // padding: 20,
//         paddingHorizontal: 12,
//         backgroundColor: color.appTextBackgoundColor,
//         borderRadius: 4
//     },
//     container: {
//         marginRight: 10, fontSize: 20, color: color.appWhiteColor, fontWeight: '600',
//     },

// });
// export default MyCards;

import React, { useState, useRef, useEffect, useContext } from "react";
import { View, Switch, Image, Text, TextInput, ImageBackground, StyleSheet, TouchableOpacity, _Text, ScrollView, FlatList, ActivityIndicator } from "react-native";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import Header from "../../components/Header";
import TextView from "../../components/TextView";
import CheckBox from '@react-native-community/checkbox';
import { SafeAreaView } from "react-native-safe-area-context";
import ActionSheet, { ActionSheetRef } from "react-native-actions-sheet";
import axios from "axios";
import { AddToCart, Default_Card, Delete_Card, get_store_card, Update_Card } from "../../network/Webconstant";
import AsyncStorage from "@react-native-async-storage/async-storage";
import Carousel, { Pagination } from "react-native-snap-carousel";
import { useIsFocused } from "@react-navigation/native";
import { createShimmerPlaceholder } from 'react-native-shimmer-placeholder'
import LinearGradient from "react-native-linear-gradient";
import { Context as CardContext } from '../../context/CardContext'
import { Context as AuthContext } from '../../context/AuthContext'
import { SliderShimmer, SliderShimmerMyCard } from "../../components/Skeleton";
import { Toast } from "react-native-toast-message/lib/src/Toast";


const MyCards = ({ props, navigation }) => {
    const actionSheetRef = useRef(null);
    const actionSheetRef1 = useRef(null)
    const ref_input1 = useRef();
    const ref_input2 = useRef();
    const ref_input3 = useRef();
    const ref_input4 = useRef();

    const exp_input1 = useRef();
    const exp_input2 = useRef();
    const exp_input3 = useRef();

    const [isEnabled, setIsEnabled] = useState(true);
    // const [number, onChangeNumber] = React.useState(null);
    const [cardNumber1, setCardNumber1] = useState('')
    const [cardNumber2, setCardNumber2] = useState('')
    const [cardNumber3, setCardNumber3] = useState('')
    const [cardNumber4, setCardNumber4] = useState('')
    // const [expDate, setExpDate] = useState('')
    const [expMonth, setExpMonth] = useState('')
    const [expYear, setExpYear] = useState('')
    const [cvv, setCvv] = useState('')
    const [cardHolderName, setCardHolderName] = useState('')
    // const [token_id, setToken_Id] = useState('')
    const [fillData, setFillData] = useState([])
    const [UserID, setUserID] = useState('')
    const [cardId, setCardId] = useState('')
    const [sliderIndex, setsliderIndex] = useState(0)
    const [cardEditData, setCardEditData] = useState('')
    const [activeIndex, setActivityIndex] = useState(0)
    // const [isLoading, setIsLoading] = useState(true)
    const [loader, setLoader] = useState(false)


    useEffect(() => {
        User_Id()
        setLoader(true)
        GetToCardApi()
        setLoader(false)

    }, [])
    const { checkActiveUser, state: { Token_ID } } = useContext(AuthContext)
    const { addNewCardApi, getNewCardApi, defaultCardApi, editCardApi, deleteCardApi, state: { activityIndicator, cardData } } = useContext(CardContext)


    // save new card
    const addToCart = () => {
        if (cardHolderName.trim() == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter card holder name'
            })
        } else if (cardNumber1.trim() === '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter card number'
            })
        }
        else if (cardNumber2.trim() === '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter card number'
            })
        } else if (cardNumber3.trim() === '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter card number'
            })
        } else if (cardNumber4.trim() === '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter card number'
            })
        } else if (expMonth.trim() == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter month'
            })
        } else if (expMonth > 12) {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter valid month'
            })
        }
        else if (expYear == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter year'
            })
        } else if (expYear > 2035 || expYear < 2010) {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter valid year'
            })
        }
        else if (cvv == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter cvv'
            })
        } else {
            onSubmitSaveButton()

        }
    }

    const onSubmitSaveButton = () => {
        console.log('handle add to card', Token_ID)
        let data = { card_holder_name: cardHolderName, card_number: cardNumber1 + cardNumber2 + cardNumber3 + cardNumber4, month: expMonth, year: expYear, cvv: cvv }
        let headers = { Authorization: `Bearer ${Token_ID}` }

        addNewCardApi(data, headers, () => {
            actionSheetRef.current?.hide()
            setLoader(false)
            GetToCardApi()
            setCardHolderName('')
            setCardNumber2('')
            setCardNumber1('')
            setCardNumber3('')
            setCardNumber4('')
            setExpMonth('')
            setExpYear('')
            setCvv('')
        })

    }
    const User_Id = async () => {
        let User_ID = await AsyncStorage.getItem('USER_ID')
        console.log('user-idddd', UserID)
        setUserID(User_ID)
    }

    // const toggleSwitch = () => setIsEnabled(previousState => !previousState);

    const toggleSwitch = async () => {
        let data = { card_id: cardData[sliderIndex]?.id }
        let headers = { Authorization: `Bearer ${Token_ID}` }
        defaultCardApi(data, headers, () => {
            setIsEnabled(previousState => !previousState)
            GetToCardApi()
        })

    }
    // let controller = new AbortController();
    // console.log('====card', cardData)
    const GetToCardApi = () => {

        let data = { propertyid: UserID }
        let headers = { Authorization: `Bearer ${Token_ID}` }
        getNewCardApi(data, headers, () => {

        })


    }

    const ShimmerPlaceholder = createShimmerPlaceholder(LinearGradient)

    // console.log('fillll', fillData)
    const renderItem = ({ item, index }) => {
        return (
            <>
                {
                    activityIndicator ? (<SliderShimmerMyCard />)
                        :
                        // <ImageBackground source={Images.CardDesignIcon} style={{ width: 200, marginHorizontal: 20, borderWidth: 1, borderRadius: 10 }}>
                        <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={{ marginHorizontal: 20, borderRadius: 15, marginTop: 20, height: 192 }} >


                            <View style={{ marginTop: 10, marginLeft: 10, marginRight: 10, }}>
                                <Text style={{ color: color.appWhiteColor, fontSize: 20 }}>MASTERCARD</Text>
                                <View style={{ flexDirection: 'row', marginTop: 15 }}>
                                    <Text style={styles.container}>{item.card_number}</Text>
                                </View>
                                <View style={{ flexDirection: 'row', marginTop: 10 }}>
                                    <View >
                                        <Text style={styles.container}>Valid Thru</Text>
                                        <Text style={styles.container}>{item.month}/{item.year}</Text>
                                    </View>
                                    <View style={{ height: width * (40 / 375), backgroundColor: color.appWhiteColor, justifyContent: 'center', borderRadius: 10, marginHorizontal: 10, marginTop: 8 }}>
                                        <Text style={{ marginHorizontal: 20, fontSize: 20, color: color.appOrangeColor, fontWeight: '500' }}>{item.cvv}</Text>
                                    </View>
                                </View>
                                <View style={{ justifyContent: 'space-between', flexDirection: 'row', marginTop: 10 }}>
                                    <Text style={styles.container}>{item.card_holder_name}</Text>
                                    <Image source={Images.cardTypeIcon} />
                                </View>
                            </View>
                        </LinearGradient>

                    // </ImageBackground>

                }
            </>
        )
    }

    const pagination = () => {
        return (
            <Pagination
                dotsLength={cardData.length}
                activeDotIndex={activeIndex}
                containerStyle={{
                    //    marginBottom: 10
                }}

                dotStyle={{
                    width: 11,
                    height: 11,
                    borderRadius: 10,
                    //   marginHorizontal: 8,
                    // backgroundColor: '#3C317D',
                    marginHorizontal: -5,
                    marginVertical:-5
                }}
                inactiveDotStyle={{
                    // Define styles for inactive dots here
                    backgroundColor: '#F99428',
                    height: 11,
                    width: 11
                }}
                
                inactiveDotOpacity={0.5}
                inactiveDotScale={0.6}
                inactiveDotColor='#F99428'
                dotColor="#3C317D"


            />
        );
    };


    const EditCardBtn = () => {
        if (cardData[sliderIndex]?.id) {
            EditData();
            setExpMonth(cardData[sliderIndex]?.month);
            setExpYear(cardData[sliderIndex]?.year);
            setCvv(cardData[sliderIndex]?.cvv);
            actionSheetRef1.current?.show()
        } else {
            alert("Please add new card")
        }

    }

    // console.log(expYear, "======================");
    const EditData = () => {
        setCardHolderName(cardData[sliderIndex]?.card_holder_name);
        setCardNumber2(cardData[sliderIndex]?.card_number.slice(0, 3));
        setCardNumber1(cardData[sliderIndex]?.card_number.slice(3, 7));
        setCardNumber3(cardData[sliderIndex]?.card_number.slice(7, 11));
        setCardNumber4(cardData[sliderIndex]?.card_number.slice(11, 15));
    }

    const editCardValidation = () => {
        if (cardHolderName.trim() == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter card holder name'
            })
        } else if (cardNumber1.trim() === '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter card number'
            })
        }
        else if (cardNumber2.trim() === '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter card number'
            })
        } else if (cardNumber3.trim() === '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter card number'
            })
        } else if (cardNumber4.trim() === '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter card number'
            })
        } else if (expMonth.trim() == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter month'
            })
        } else if (expMonth > 12) {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter valid month'
            })
        }
        else if (expYear == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter year'
            })
        } else if (expYear > 2035 || expYear < 2010) {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter valid year'
            })
        }
        else if (cvv == '') {
            Toast.show({
                type: 'error',
                text1: 'Shortlet',
                text2: 'please enter cvv'
            })
        } else {
            EditCardApi()
        }
    };


    // // setCardEditData(fillData[e]?.id)
    // console.log(sliderIndex, '====cardData', cardData[sliderIndex])
    // let asd = cardData.filter(x => x.id === cardId)


    const EditCardApi = () => {
        setCardEditData(cardData[sliderIndex])
        let data = { card_holder_name: cardHolderName, card_number: cardNumber1 + cardNumber2 + cardNumber3 + cardNumber4, month: expMonth, year: expYear, cvv: cvv, card_id: cardData[sliderIndex]?.id }
        let headers = { Authorization: `Bearer ${Token_ID}` }
        editCardApi(data, headers, () => {
            actionSheetRef1.current?.hide()
            GetToCardApi()
            setCardHolderName(cardData[sliderIndex]?.year)
            setCardNumber2(cardData[sliderIndex]?.card_number.slice(0, 3))
            setCardNumber1(cardData[sliderIndex]?.slice(3, 7))
            setCardNumber3(cardData[sliderIndex]?.slice(7, 11))
            setCardNumber4(cardData[sliderIndex]?.slice(11, 15))
            setExpMonth(cardData[sliderIndex]?.month)
            setExpYear(cardData[sliderIndex]?.year)
            setCvv(cardData[sliderIndex]?.cvv)

        })
        // axios({
        //     method: 'post',
        //     url: Update_Card,
        //     data: { card_holder_name: cardHolderName, card_number: cardNumber1 + cardNumber2 + cardNumber3 + cardNumber4, month: expMonth, year: expYear, cvv: cvv, card_id: cardId },
        //     headers: { Authorization: `Bearer ${token_id}` }
        // }).then(
        //     function (response) {
        //         console.log('update card resssss', response.data)
        //         if (response.data.status == true) {
        //             alert(response.data.message)
        //             actionSheetRef1.current?.hide()
        //             GetToCardApi()
        //             setCardHolderName('')
        //             setCardNumber1('')
        //             setCardNumber2('')
        //             setCardNumber3('')
        //             setCardNumber4('')
        //             setCvv('')
        //             setExpMonth('')
        //             setExpYear('')
        //         } else {
        //             alert(response.data.message.card_holder_name || response.data.message.card_number || response.data.message.cvv || response.data.message.month || response.data.message.year || response.data.message)
        //         }
        //     }
        // ).catch((e) => {
        //     console.log('error is ', e);
        // })
    }

    const onSubmitDeleteCardApi = () => {
        let data = { card_id: cardData[sliderIndex]?.id }
        let headers = { Authorization: `Bearer ${Token_ID}` }

        deleteCardApi(data, headers, () => {
            GetToCardApi()
        })
        // axios({
        //     method: 'post',
        //     url: Delete_Card,
        //     data: { card_id: cardId },
        //     headers: { Authorization: `Bearer ${token_id}` }
        // }).then(
        //     function (res) {
        //         console.log('delete api is ', res.data)
        //         if (res.data.status === true) {
        //             alert(res.data.message)
        //             GetToCardApi()
        //         } else {
        //             alert(res.data.message.card_id) || alert(res.data.message)
        //         }
        //     }
        // )
    };


    const AddNewCardBtn = () => {

        setCardHolderName('')
        setCardNumber2('')
        setCardNumber1('')
        setCardNumber3('')
        setCardNumber4('')
        setExpMonth('')
        setExpYear('')
        setCvv('')
        actionSheetRef.current?.show()
    }


    return (
        <>

            <View style={commonStyles.container}>

                <Header props={props} Heading={'My Cards'} onPress={() => navigation.navigate('Account')} />
                <ScrollView contentContainerStyle={{ marginTop: 0 }}>
                    {
                        cardData.length === 0 ? <Text style={{ fontSize: 24, alignSelf: 'center', backgroundColor: color.appYellowColor, height: 200, width: '90%', borderRadius: 20, textAlign: 'center', alignItems: 'center', marginTop: 10, color: '#fff' }}>No Card Here!</Text>
                            :
                            <Carousel
                                // ref={(c) => { this._carousel = c; }}
                                pinchGestureEnabled={false}
                                enableMomentum={true}
                                data={cardData}
                                renderItem={renderItem}
                                sliderWidth={width}
                                itemWidth={width}
                                layoutCardOffset={6}
                                // sliderHeight={200}
                                // ref={carouselRef}
                                bounces={false}
                                layout={"default"}
                                // autoplay={true}
                                // loop={true}
                                enableSnap={true}
                                // loopClonesPerSide={5}
                                inactiveSlideOpacity={1}
                                inactiveSlideScale={1}
                                onSnapToItem={(e) => {
                                    setCardId(fillData[e]?.id)
                                    setsliderIndex(e)
                                    setActivityIndex(e),
                                        // setIsEnabled(e === fillData[e].defalult_card)
                                        setIsEnabled(e == cardData[e]?.defalult_card)
                                    console.log('eeee', e, cardData[e]?.defalult_card)
                                    console.log("-=124-=-=", fillData[e]?.id, '====cardData', cardData)
                                    // let tempData = fillData
                                    // for (let i = 0; i < tempData.length; i++) {
                                    //     const element = tempData[i];
                                    //     console.log('element is',element.defalult_card)
                                    //     if(element.defalult_card === cardId ){
                                    //         element.defalult_card = !element.defalult_card
                                    //     }
                                    // }
                                    // setFillData(tempData)

                                }}

                            />
                    }
                    {cardData.length > 0 && pagination()}

                    <TouchableOpacity style={{ borderRadius: 10, marginTop: 0, height: 60, marginHorizontal: 20, padding: 16, backgroundColor: color.appLightOrangeColor, borderColor: '#F99428', borderWidth: 1, flexDirection: "row", justifyContent: "space-between", alignItems: 'center' }}>
                        <Text style={{ color: color.appOrangeColor, fontSize: 14 }}>Make Default</Text>
                        {/* <Image source={Images.ArrowIcons} /> */}
                        <View style={{ height: 28, backgroundColor: isEnabled ? 'orange' : '#767577', width: 50, borderRadius: 20, justifyContent: 'center' }}>
                            <Switch
                                trackColor={{ false: "#767577", true: 'orange' }}
                                thumbColor={isEnabled ? "#fff" : "#f5dd4b"}
                                ios_backgroundColor="#3e3e3e"
                                onValueChange={() => toggleSwitch()}
                                value={isEnabled}
                            />
                        </View>
                    </TouchableOpacity>

                    <View style={{ flexDirection: 'row', marginHorizontal: 20, justifyContent: 'space-between',alignItems:'center' }}>

                        <TouchableOpacity onPress={() => EditCardBtn()} style={{ borderRadius: 10, marginTop: 20, height: 50, backgroundColor: color.appLightOrangeColor, borderColor: '#F99428', borderWidth: 1, flexDirection: "row", justifyContent: "flex-start", alignItems: 'center',width:150 }} >
                            <Image source={Images.EditCardIcon} style={{ marginLeft: width * (9 / 375) }} />
                            <Text style={{ color: color.appOrangeColor, fontSize: 14, marginHorizontal: width * (12 / 375) }}>Edit Card</Text>


                        </TouchableOpacity>

                        <TouchableOpacity onPress={() => onSubmitDeleteCardApi()} style={{ borderRadius: 10, marginTop: 20, height: 50, backgroundColor: color.appLightOrangeColor, borderColor: '#F99428', borderWidth: 1, flexDirection: "row", justifyContent: "flex-start", alignItems: 'center',width:150, }}>
                            <Image source={Images.deleteIcon} style={{ marginLeft: width * (9 / 375) }} />
                            <Text style={{ color: color.appOrangeColor, fontSize: 14, marginHorizontal: width * (12 / 375) }}>Delete</Text>
                        </TouchableOpacity>

                    </View>
                    <View style={{ justifyContent: "flex-end", bottom: 0, flex: 1, alignItems: 'center' }}>
                        <TouchableOpacity onPress={() => AddNewCardBtn()} style={{
                            
                            marginTop: 100,
                            backgroundColor: color.appBlueColor,
                            width: '90%',
                            padding: 10,
                            borderRadius: 10,
                            justifyContent: 'center',
                            alignSelf: 'center',
                            marginBottom: width * (20 / 375),
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

                            <Text style={{ textAlign: "center", fontSize: 15, color: color.appWhiteColor }}>Add New Card</Text>

                        </TouchableOpacity>
                    </View>

                    <ActionSheet ref={actionSheetRef} >

                        {/*  edit card */}
                        <View style={{ marginTop: 8 }} >
                            <View style={{ paddingVertical: 12, marginHorizontal: 20 }}>
                                <Text style={{ fontSize: 20, fontWeight: '500', marginBottom: 10, color: color.primaryColorBlack }}>
                                    Card Detail
                                </Text>
                            </View>
                            <View style={{ borderWidth: 0.5, borderColor: '#e3e3e3' }} />
                            <View style={{ marginHorizontal: 20 }}>
                                <Text style={{ color: color.primaryColorBlack, fontWeight: '600', fontSize: 15, marginTop: 14 }}>Card Holder Name</Text>
                                <TextInput
                                    placeholder="Enter your name"
                                    style={{
                                        height: 50,
                                        marginHorizontal: 0,
                                        // padding: 20,
                                        paddingHorizontal: 12,
                                        backgroundColor: color.appTextBackgoundColor,
                                        borderRadius: 4,
                                        width: '100%',
                                        marginTop: 5
                                    }}
                                    value={cardHolderName}
                                    onChangeText={(text) => setCardHolderName(text)}
                                />
                            </View>
                            <Text style={{ fontSize: 15, fontWeight: '500', marginBottom: 10, color: color.primaryColorBlack, marginTop: 14, marginHorizontal: 20 }}>
                                Card Number
                            </Text>
                        </View>
                        <View style={{ justifyContent: 'space-around', flexDirection: 'row', marginHorizontal: 8 }}>
                            <View >
                                <TextInput
                                    style={styles.input}
                                    onChangeText={(text) => {
                                        setCardNumber1(text.replace(/[^0-9]/, ''))
                                        if (text.length == 4) {
                                            ref_input2.current.focus()
                                        }
                                    }

                                    }
                                    value={cardNumber1}
                                    placeholder="XXXX"
                                    keyboardType='numeric'
                                    maxLength={4}

                                    autoCorrect={false}
                                    autoCapitalize='none'
                                    autoFocus={true}
                                    returnKeyType="next"
                                    onSubmitEditing={() => ref_input2.current.focus()}
                                    ref={ref_input1}
                                    onBlur={() => ref_input2.current.focus()}
                                />
                            </View>
                            <View >
                                <TextInput
                                    style={styles.input}
                                    // onChangeText={onChangeNumber}
                                    value={cardNumber2}
                                    onChangeText={(text) => {
                                        setCardNumber2(text.replace(/[^0-9]/, ''));
                                        if (text.length == 4) {
                                            ref_input3.current.focus()
                                        }
                                        if (text.length == 0) {
                                            ref_input1.current.focus()
                                        }
                                    }}
                                    placeholder="XXXX"
                                    keyboardType='numeric'
                                    maxLength={4}
                                    returnKeyType="next"
                                    onSubmitEditing={() => ref_input2.current.focus()}
                                    ref={ref_input2}
                                    autoCorrect={false}
                                    autoCapitalize='none'
                                    autoFocus={true}

                                />
                            </View>
                            <View >
                                <TextInput
                                    style={styles.input}
                                    // onChangeText={onChangeNumber}
                                    onChangeText={(text) => {
                                        setCardNumber3(text.replace(/[^0-9]/, ''));
                                        if (text.length == 4) {
                                            ref_input4.current.focus()
                                        }
                                        if (text.length == 0) {
                                            ref_input2.current.focus()
                                        }
                                    }}
                                    value={cardNumber3}
                                    placeholder="XXXX"
                                    keyboardType='numeric'
                                    maxLength={4}

                                    returnKeyType="next"
                                    onSubmitEditing={() => ref_input4.current.focus()}
                                    ref={ref_input3}
                                    autoCorrect={false}
                                    autoCapitalize='none'
                                    autoFocus={true}

                                />
                            </View>
                            <View >
                                <TextInput
                                    style={styles.input}
                                    // onChangeText={onChangeNumber}
                                    onChangeText={(text) => {
                                        setCardNumber4(text.replace(/[^0-9]/, ''));
                                        if (text.length == 0) {
                                            ref_input3.current.focus()
                                        }
                                    }}
                                    value={cardNumber4}
                                    placeholder="XXXX"
                                    keyboardType='numeric'
                                    maxLength={4}

                                    returnKeyType="next"
                                    onSubmitEditing={() => ref_input4.current.focus()}
                                    ref={ref_input4}
                                />
                            </View>


                        </View>

                        <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginHorizontal: 20, marginTop: 10 }}>
                            <View >
                                <Text style={{ color: color.primaryColorBlack, marginBottom: 10 }}>Expiry Date</Text>
                                <TextInput
                                    style={[styles.input, { width: 110 }]}
                                    // onChangeText={onChangeNumber}
                                    onChangeText={(text) => {
                                        setExpMonth(text.replace(/[^0-9]/, ''));
                                        if (text.length == 2) {
                                            exp_input2.current.focus()
                                        }
                                    }}
                                    value={expMonth}
                                    placeholder="Month"
                                    keyboardType="number-pad"
                                    maxLength={2}

                                    returnKeyType="next"
                                    onSubmitEditing={() => exp_input2.current.focus()}
                                    ref={exp_input1}
                                    autoCorrect={false}
                                    autoCapitalize='none'
                                    autoFocus={true}
                                />
                            </View>
                            <View style={{ alignSelf: 'flex-start' }}>

                                <TextInput
                                    style={{
                                        width: 110, marginTop: 30, height: 40,
                                        // margin: 12,
                                        // padding: 20,
                                        paddingHorizontal: 12,
                                        backgroundColor: color.appTextBackgoundColor,
                                        borderRadius: 4,
                                        marginHorizontal: 5
                                    }}
                                    value={expYear}

                                    // onChangeText={onChangeNumber}
                                    onChangeText={(text) => {
                                        setExpYear(text.replace(/[^0-9]/, ''));
                                        if (text.length == 4) {
                                            exp_input3.current.focus()
                                        }
                                        if (text.length == 0) {
                                            exp_input1.current.focus()
                                        }
                                    }}
                                    placeholder="Year"
                                    keyboardType="number-pad"
                                    maxLength={4}

                                    returnKeyType="next"
                                    onSubmitEditing={() => exp_input3.current.focus()}
                                    ref={exp_input2}
                                    autoCorrect={false}
                                    autoCapitalize='none'
                                    autoFocus={true}
                                />
                            </View>
                            <View style={{}} >
                                <Text style={{ color: color.primaryColorBlack, marginBottom: 10 }}>CVV</Text>
                                <TextInput
                                    style={[styles.input, { width: 100 }]}
                                    // onChangeText={onChangeNumber}
                                    onChangeText={(text) => {
                                        setCvv(text.replace(/[^0-9]/, ''));
                                        if (text.length == 0) {
                                            exp_input2.current.focus()
                                        }
                                    }}
                                    value={cvv}
                                    placeholder="XXX"
                                    keyboardType="number-pad"
                                    maxLength={3}
                                    ref={exp_input3}

                                // returnKeyType="next"
                                // onSubmitEditing={() => exp_input3.current.focus()}
                                // ref={ref_input2}
                                // autoCorrect={false}
                                // autoCapitalize='none'
                                // autoFocus={true}
                                />
                            </View>
                        </View>

                        <TouchableOpacity
                            //  onPress={() => props.navigation.navigate('Logins')}
                            onPress={() =>
                                addToCart()}
                            style={{
                                marginTop: 20,
                                backgroundColor: color.appBlueColor,
                                width: '90%',
                                padding: 10,
                                borderRadius: 10,
                                justifyContent: 'center',
                                alignSelf: 'center',
                                marginBottom: 30,
                                height: 50
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

                            <Text style={{ textAlign: "center", fontSize: 15, color: color.appWhiteColor }}>Save</Text>
                        </TouchableOpacity>

                    </ActionSheet>
                    <ActionSheet ref={actionSheetRef1} >
                        <View style={{ marginTop: 8 }} >
                            <View style={{ paddingVertical: 12, marginHorizontal: 20 }}>
                                <Text style={{ fontSize: 20, fontWeight: '500', marginBottom: 10, color: color.primaryColorBlack }}>
                                    Card Detail
                                </Text>
                            </View>
                            <View style={{ borderWidth: 0.5, borderColor: '#e3e3e3' }} />
                            <View style={{ marginHorizontal: 20 }}>
                                <Text style={{ color: color.primaryColorBlack, fontWeight: '600', fontSize: 15, marginTop: 10 }}>Card Holder Name</Text>
                                <TextInput
                                    placeholder="Enter your name"
                                    style={{
                                        height: 50,
                                        marginHorizontal: 0,
                                        // padding: 20,
                                        paddingHorizontal: 12,
                                        backgroundColor: color.appTextBackgoundColor,
                                        borderRadius: 4,
                                        width: '100%',
                                        marginTop: 5
                                    }}
                                    // value={cardData.card_holder_name}
                                    value={cardHolderName}
                                    onChangeText={(text) => setCardHolderName(text)}
                                />
                            </View>
                            <Text style={{ fontSize: 15, fontWeight: '500', marginBottom: 10, color: color.primaryColorBlack, marginTop: 10, marginHorizontal: 20 }}>
                                Card Number
                            </Text>
                        </View>
                        <View style={{ justifyContent: 'space-around', flexDirection: 'row', marginHorizontal: 8 }}>
                            <View >
                                <TextInput
                                    style={styles.input}
                                    onChangeText={(text) => {
                                        setCardNumber1(text)
                                        if (text.length == 4) {
                                            ref_input2.current.focus()
                                        }
                                    }}
                                    value={cardNumber1}
                                    placeholder="XXXX"
                                    keyboardType='numeric'
                                    maxLength={4}
                                    multiline
                                    // onSubmitEditing={() => alert(`Welcome to ${cardNumber1}`)}

                                    autoCorrect={false}
                                    autoCapitalize='none'
                                    autoFocus={true}
                                    returnKeyType="next"
                                    onSubmitEditing={() => ref_input2.current.focus()}
                                    ref={ref_input1}
                                    onBlur={() => ref_input2.current.focus()}
                                />
                            </View>
                            <View >
                                <TextInput
                                    style={styles.input}
                                    // onChangeText={onChangeNumber}
                                    value={cardNumber2}
                                    onChangeText={(text) => {
                                        setCardNumber2(text.replace(/[^0-9]/, ''));
                                        if (text.length == 4) {
                                            ref_input3.current.focus()
                                        }
                                        if (text.length == 0) {
                                            ref_input1.current.focus()
                                        }
                                    }}
                                    placeholder="XXXX"
                                    keyboardType='numeric'
                                    maxLength={4}
                                    returnKeyType="next"
                                    onSubmitEditing={() => ref_input3.current.focus()}
                                    ref={ref_input2}
                                    autoCorrect={false}
                                    autoCapitalize='none'
                                    autoFocus={true}
                                />
                            </View>
                            <View >
                                <TextInput
                                    style={styles.input}
                                    // onChangeText={onChangeNumber}
                                    onChangeText={(text) => {
                                        setCardNumber3(text.replace(/[^0-9]/, ''));
                                        if (text.length == 4) {
                                            ref_input4.current.focus()
                                        }
                                        if (text.length == 0) {
                                            ref_input2.current.focus()
                                        }
                                    }}
                                    value={cardNumber3}
                                    placeholder="XXXX"
                                    keyboardType='numeric'
                                    maxLength={4}
                                    returnKeyType="next"
                                    onSubmitEditing={() => ref_input4.current.focus()}
                                    ref={ref_input3}
                                    autoCorrect={false}
                                    autoCapitalize='none'
                                    autoFocus={true}
                                />
                            </View>
                            <View >
                                <TextInput
                                    style={styles.input}
                                    // onChangeText={onChangeNumber}
                                    onChangeText={(text) => {
                                        setCardNumber4(text.replace(/[^0-9]/, ''));
                                        if (text.length == 0) {
                                            ref_input3.current.focus()
                                        }
                                    }}
                                    value={cardNumber4}
                                    placeholder="XXXX"
                                    keyboardType='numeric'
                                    maxLength={4}

                                    returnKeyType="next"
                                    onSubmitEditing={() => ref_input4.current.focus()}
                                    ref={ref_input4}

                                />
                            </View>


                        </View>

                        <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginHorizontal: 20, marginTop: 10 }}>
                            <View >
                                <Text style={{ color: color.primaryColorBlack, marginBottom: 10 }}>Expiry Date</Text>
                                <TextInput
                                    style={[styles.input, { width: 110 }]}
                                    // onChangeText={onChangeNumber}
                                    onChangeText={(text) => {
                                        setExpMonth(text.replace(/[^0-9]/, ''));
                                        if (text.length == 2) {
                                            exp_input2.current.focus()
                                        }
                                    }}
                                    value={expMonth}
                                    placeholder="Month"
                                    keyboardType='numeric'
                                    maxLength={2}

                                    returnKeyType="next"
                                    onSubmitEditing={() => exp_input2.current.focus()}
                                    ref={exp_input1}
                                    autoCorrect={false}
                                    autoCapitalize='none'
                                    autoFocus={true}

                                />
                            </View>
                            <View style={{ alignSelf: 'flex-start' }}>
                                <TextInput
                                    value={expYear.toString()}
                                    style={{
                                        width: 110, marginTop: 30, height: 40,
                                        // margin: 12,
                                        // padding: 20,
                                        paddingHorizontal: 12,
                                        backgroundColor: color.appTextBackgoundColor,
                                        borderRadius: 4,
                                        marginHorizontal: 5
                                    }}
                                    // onChangeText={onChangeNumber}
                                    onChangeText={(text) => {
                                        setExpYear(text.replace(/[^0-9]/, ''));
                                        if (text.length == 4) {
                                            exp_input3.current.focus()
                                        }
                                        if (text.length == 0) {
                                            exp_input1.current.focus()
                                        }
                                    }}

                                    placeholder="Year"
                                    keyboardType='numeric'
                                    maxLength={4}

                                    returnKeyType="next"
                                    onSubmitEditing={() => exp_input3.current.focus()}
                                    ref={exp_input2}
                                    autoCorrect={false}
                                    autoCapitalize='none'
                                    autoFocus={true}
                                />
                            </View>
                            <View style={{}} >
                                <Text style={{ color: color.primaryColorBlack, marginBottom: 10 }}>CVV</Text>
                                <TextInput
                                    style={[styles.input, { width: 100 }]}
                                    // onChangeText={onChangeNumber}
                                    onChangeText={(text) => {
                                        setCvv(text.replace(/[^0-9]/, ''));
                                        if (text.length == 0) {
                                            exp_input2.current.focus()
                                        }
                                    }}
                                    value={cvv.toString()}
                                    placeholder="XXX"
                                    keyboardType='numeric'
                                    maxLength={3}
                                    ref={exp_input3}
                                />
                            </View>
                        </View>

                        <TouchableOpacity
                            //  onPress={() => props.navigation.navigate('Logins')}
                            onPress={() => editCardValidation()}
                            style={{
                                marginTop: 20,
                                backgroundColor: color.appBlueColor,
                                width: '90%',
                                padding: 10,
                                borderRadius: 10,
                                justifyContent: 'center',
                                alignSelf: 'center',
                                marginBottom: 30,
                                height: 50
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

                            <Text style={{ textAlign: "center", fontSize: 15, color: color.appWhiteColor }}>Save</Text>
                        </TouchableOpacity>

                        {/* <View style={{ marginTop: 8, marginHorizontal: 15, }} >
                                <View style={{ borderBottomColor: color.appTextBackgoundColor, borderBottomWidth: 1, marginBottom: 20 }}>
                                    <Text style={{ fontSize: 15, fontWeight: '500', marginBottom: 10, color: color.primaryColorBlack }}>
                                        Card Detail
                                    </Text>
                                </View>
                                <View>
                                    <Text style={{ color: color.primaryColorBlack, fontWeight: '600', fontSize: 15 }}>Card Holder Name</Text>
                                    <TextInput
                                        placeholder="Enter your name"
                                        style={{
                                            height: 50,
                                            marginHorizontal: 0,
                                            // padding: 20,
                                            paddingHorizontal: 12,
                                            backgroundColor: color.appTextBackgoundColor,
                                            borderRadius: 4,
                                            width: '100%'
                                        }}
                                        value={cardHolderName}
                                        onChangeText={(text) => setCardHolderName(text)}
                                    />
                                </View>
                                <Text style={{ fontSize: 15, fontWeight: '500', marginBottom: 10, color: color.primaryColorBlack, marginTop: 10 }}>
                                    Card Number
                                </Text>
                            </View>
                            <View style={{ justifyContent: 'space-around', flexDirection: 'row' }}>
                                <View >
                                    <TextInput
                                        style={styles.input}
                                        onChangeText={(text) => setCardNumber1(text)}
                                        value={cardNumber1}
                                        placeholder="XXXX"
                                        keyboardType='numeric'
                                        maxLength={4}
                                    />
                                </View>
                                <View >
                                    <TextInput
                                        style={styles.input}
                                        // onChangeText={onChangeNumber}
                                        value={cardNumber2}
                                        onChangeText={(text) => setCardNumber2(text)}
                                        placeholder="XXXX"
                                        keyboardType='numeric'
                                        maxLength={4}
                                    />
                                </View>
                                <View >
                                    <TextInput
                                        style={styles.input}
                                        // onChangeText={onChangeNumber}
                                        onChangeText={(text) => setCardNumber3(text)}
                                        value={cardNumber3}
                                        placeholder="XXXX"
                                        keyboardType='numeric'
                                        maxLength={4}
                                    />
                                </View>
                                <View >
                                    <TextInput
                                        style={styles.input}
                                        // onChangeText={onChangeNumber}
                                        onChangeText={(text) => setCardNumber4(text)}
                                        value={cardNumber4}
                                        placeholder="XXXX"
                                        keyboardType='numeric'
                                        maxLength={4}
                                    />
                                </View>


                            </View>

                            <View style={{ flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' }}>
                                <View >
                                    <Text style={{ marginLeft: 20, color: color.primaryColorBlack }}>Expiry Date</Text>
                                    <TextInput
                                        style={[styles.input, { width: 80 }]}
                                        // onChangeText={onChangeNumber}
                                        onChangeText={(text) => setExpMonth(text)}
                                        value={expMonth}
                                        placeholder="Month"
                                        keyboardType='default'
                                        maxLength={2}
                                    />
                                </View>
                                <View style={{ alignSelf: 'flex-start' }}>
                                    <TextInput
                                        style={{
                                            width: 80, marginTop: 32, height: 40,
                                            margin: 12,
                                            // padding: 20,
                                            paddingHorizontal: 12,
                                            backgroundColor: color.appTextBackgoundColor,
                                            borderRadius: 4,
                                            marginHorizontal: 20
                                        }}
                                        // onChangeText={onChangeNumber}
                                        onChangeText={(text) => setExpYear(text)}
                                        value={expYear}
                                        placeholder="Year"
                                        keyboardType='default'
                                        maxLength={4}
                                    />
                                </View>
                                <View style={{ paddingRight: 20 }} >
                                    <Text style={{ marginLeft: 20, color: color.primaryColorBlack }}>CVV</Text>
                                    <TextInput
                                        style={styles.input}
                                        // onChangeText={onChangeNumber}
                                        onChangeText={(text) => setCvv(text)}
                                        value={cvv}
                                        placeholder="XXX"
                                        keyboardType='default'
                                        maxLength={3}
                                    />
                                </View>
                            </View>

                            <TouchableOpacity
                                //  onPress={() => props.navigation.navigate('Logins')}
                                onPress={() => EditCardApi()}
                                style={{
                                    marginTop: 20,
                                    backgroundColor: color.appBlueColor,
                                    width: width - 30,
                                    padding: 10,
                                    borderRadius: 10,
                                    justifyContent: 'center',
                                    alignSelf: 'center',
                                    marginBottom: 30,
                                    height: 50
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

                                <Text style={{ textAlign: "center", fontSize: 15, color: color.appWhiteColor }}>Save</Text>
                            </TouchableOpacity> */}

                    </ActionSheet>
                </ScrollView>
            </View>
            {/* } */}
        </>

    )
}

const styles = StyleSheet.create({
    input: {
        height: 40,
        // margin: 12,
        // padding: 20,
        paddingHorizontal: 12,
        backgroundColor: color.appTextBackgoundColor,
        borderRadius: 4
    },
    container: {
        marginRight: 10, fontSize: 20, color: color.appWhiteColor, fontWeight: '600',
    },

});
export default MyCards;