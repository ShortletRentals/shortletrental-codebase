// import React, { useRef, useState, useEffect } from "react";
// import { View, Image, Text, StyleSheet, ImageBackground, TouchableOpacity, StatusBar, FlatList, ScrollView, Alert, BackHandler } from "react-native";
// // import { ScrollView } from "react-native-gesture-handler";
// import { color, height, width } from "../../styles/colors";
// import Images from "../../styles/Images";
// import { commonStyles } from "./../../styles/style";
// import { useIsFocused, useNavigation, useRoute } from "@react-navigation/native";

// import PagerView from 'react-native-pager-view';
// import CheckBoxs from "../../components/CheckBox";
// import ActionSheet, { ActionSheetRef } from "react-native-actions-sheet";
// import LinearGradient from "react-native-linear-gradient";
// import { SafeAreaView } from "react-native-safe-area-context";
// import CheckBox from '@react-native-community/checkbox'
// import { SliderBox } from "react-native-image-slider-box";
// import Header from "../../components/Header";
// import BottomTab from "../../navigators/BottomTab";
// import CustomTabBar from "../../navigators/CustomTabBar";
// import { Rating } from "react-native-ratings";
// import BecomeAhostTab from "../../navigators/BecomeAhostTab";
// const renderData = [
//     { status: Images.activeIcon, image: Images.calenderIcon, title: 'Modest 1-bedroom apartment', Hotelname: 'Lekki - Apartment', Description: 'NGN 200.00', Name: ' night' },
//     { status: Images.inActiveIcon, image: Images.House, title: 'Modular 2-bedroom apartment', Hotelname: 'Lekki -full Flat', Description: 'NGN 250.00', Name: ' day' },
//     { status: Images.activeIcon, image: Images.fbIcon, title: 'full furnished bedroom flat', Hotelname: 'fully furnished dta', Description: 'NGN 300.00', Name: ' night' },
//     { status: Images.inActiveIcon, image: Images.fbIcon, title: 'semi furnisgedd', Hotelname: 'semi furnished', Description: 'NGN 350.00', Name: ' night' }
// ]
// const BecomeHostHomeScreen = (props) => {
//     const route = useRoute()
//     const navigation = useNavigation()
//     const actionSheetRef = useRef(null);
//     const [toggleCheckBox, setToggleCheckBox] = useState(false)
//     const [isSelect, setIsSelect] = useState(false)
//     const DATA = [
//         {
//             title: 'National Parks',
//             image: Images.Solid
//         },
//         {
//             title: 'Cabins',
//             image: Images.chairSelectedIcon
//         },
//         {
//             title: 'Truehouse',
//             image: Images.chairSelectedIcon
//         },
//         {
//             title: 'Truehouse',
//             image: Images.chairSelectedIcon
//         },
//         {
//             title: 'Truehouse',
//             image: Images.chairSelectedIcon
//         },
//     ]
//     const Bathroom = [
//         {
//             number: 1
//         },
//         {
//             number: 2
//         },
//         {
//             number: 3
//         },
//         {
//             number: 4
//         },
//         {
//             number: 5
//         },
//     ]
//     const renderItem = ({ item, index }) => {
//         return (
//             <TouchableOpacity onPress={() => setIsSelect(index)} style={[commonStyles.flatView, { backgroundColor: isSelect !== index ? color.white : color.appOrangeColor }]}>
//                 <Image source={item.image} style={{ marginHorizontal: 6 }} />
//                 <Text style={[commonStyles.flatText, { color: isSelect !== index ? '#000' : '#fff' }]}>{item.title}</Text>
//             </TouchableOpacity>
//         )
//     }
//     useEffect(() => {
//         console.log("=====",route?.params?.type)
//         // const backAction = () => {
//         //   Alert.alert("Hold on!", "Are you sure you want to go back?", [
//         //     {
//         //       text: "Cancel",
//         //       onPress: () => null,
//         //       style: "cancel"
//         //     },
//         //     { text: "YES", onPress: () => BackHandler.exitApp() }
//         //   ]);
//         //   return true;
//         // };

//         // const backHandler = BackHandler.addEventListener(
//         //   "hardwareBackPress",
//         //   backAction
//         // );

//         // return () => backHandler.remove();
//       }, []);
//     return (
//         <SafeAreaView>
//             <ScrollView>
//                 <StatusBar backgroundColor={color.appYellowColor} />
//                 <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={{ flexDirection: 'row', height: 60, alignItems: "center", justifyContent: 'center' }} >
//                     <TouchableOpacity
//                     //  onPress={() => props.navigation.navigate('Account')}
//                      style={{height:32,width:32,backgroundColor:'#fff',borderRadius:8,marginRight:20}}>
//                         <Image source={Images.HotelImage} style={{
//                             // paddingLeft: 70,
//                             width: 32, height: 32,borderRadius:8,borderWidth:1,marginRight:30
//                         }} resizeMode='contain' />
//                     </TouchableOpacity>
//                     <TouchableOpacity 
//                     onPress={() => props.navigation.navigate('SearchProperty')}
//                     >
//                         <View style={{ flexDirection: 'row', width: width - width * (189 / 375), height: 45, alignContent: 'center', alignItems: 'center', justifyContent: 'space-between', backgroundColor: color.appWhiteColor, marginRight: 10, borderRadius: 8 }}>
//                             <Text style={{ marginLeft: 10 }}>Search</Text>
//                             <Image style={{ marginRight: 10 }} source={Images.SearchOrangeIcons} />
//                         </View>
//                     </TouchableOpacity>

//                     <View style={{ flexDirection: 'row' }}>
//                         <TouchableOpacity onPress={() => props.navigation.navigate('AddPropertyguest')} >
//                             <Image source={Images.PlusIcon} style={{ marginRight: 10, marginLeft: 5, height: 32, width: 32 }} />
//                         </TouchableOpacity>
//                         <TouchableOpacity onPress={() => actionSheetRef.current?.show()}>
//                             <Image source={Images.Filters} style={{ height: 32, width: 32,marginHorizontal:1 }} />
//                         </TouchableOpacity>
//                     </View>
//                 </LinearGradient>



              
//                 <View style={commonStyles.container}>
//                     {
//                         renderData.map((item, index) => {
//                             return (

//                                 <TouchableOpacity
//                                     key={index}
//                                     style={{ marginTop: 20 }}
//                                     // onPress={() => props.navigation.navigate('HomeDetails')}
//                                     >
//                                     <View
//                                     // style={{
//                                     //  marginTop: width * (20 / 375),
//                                     //  paddingHorizontal: width * (10 / 375),
//                                     //      marginBottom: width * (1 / 375), 
//                                     //      borderColor: '#E9E9E9', 
//                                     //      borderWidth: 1,
//                                     //       borderRadius: 12,
//                                     //        marginHorizontal: width * (20 / 375),

//                                     // }}
//                                     >
//                                         {/* <Image source={{ uri: 'https://images.pexels.com/photos/1648776/pexels-photo-1648776.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2' }} style={{
//                                             width: width * (335 / 375),
//                                             height: width * (135 / 375),
//                                             resizeMode: 'cover',
//                                             borderTopLeftRadius: 10,
//                                             borderTopRightRadius: 10,
//                                             marginRight: width * (0 / 375),
//                                             alignSelf: 'center',

//                                         }} /> */}
//                                         {/* <View >
//                                                 <Image source={item.status} style={{ top: -141, left: -12, transform: [{ rotate: '-0.5deg' }],height:50 }} />
//                                             </View> */}
//                                         <SliderBox
//                                             images={state.images}
//                                             dotColor='orange'
//                                             resizeMode='contain'

//                                             // style={{height: width * (135 / 375),width:'90%' }}
//                                             // onCurrentImagePressed={index =>
//                                             //     console.warn(`image ${index} pressed`)

//                                             // }
//                                             ImageComponentStyle={{
//                                                 borderTopLeftRadius: 10,
//                                                 borderTopRightRadius: 10,
//                                                 width: width * (335 / 375),
//                                                 marginRight: 70,
//                                                 alignSelf: 'center',
//                                                 height: width * (135 / 375),
//                                                 resizeMode: 'cover',
//                                                 marginLeft: 70
//                                             }}


//                                         />

//                                         <View style={{
//                                             paddingHorizontal: width * (10 / 375),
//                                             marginBottom: width * (1 / 375),
//                                             borderColor: '#E9E9E9',
//                                             borderWidth: 1,
//                                             // marginBottom: 10,
//                                             borderBottomLeftRadius: 10,
//                                             borderBottomRightRadius: 10,
//                                             marginHorizontal: width * (20 / 375),
//                                         }}>
//                                             {/* <View style={{height:20,backgroundColor:color.appYellowColor,top:-120,width:80,alignSelf:'flex-start',transform:[{rotate:'-45deg'}]}}>
//     <Text>{item.status}</Text>
// </View> */}


//                                             <Text numberOfLines={1} style={{ fontSize: 20, fontWeight: '400', color: color.primaryColorBlack }}>{item.title}</Text>
//                                             <View style={{ flexDirection: "row", justifyContent: 'space-between', width: width * (300 / 375) }}>
//                                                 <View style={{ flexDirection: "row", width: width * (150 / 375), marginTop: 10 }}>
//                                                     <TouchableOpacity>
//                                                         <Image source={Images.LocationIcons} style={{
//                                                             paddingLeft: 10,
//                                                             width: 20, height: 20, resizeMode: 'contain',
//                                                         }} />
//                                                     </TouchableOpacity>
//                                                     <TouchableOpacity>
//                                                         <Text style={{ paddingLeft: 10, color: color.primaryColorBlack }}>{item.Hotelname}</Text>
//                                                     </TouchableOpacity>
//                                                 </View>
//                                                 <View style={{ flexDirection: "row", width: width * (70 / 375), marginTop: 10, justifyContent: 'flex-end', alignItems: 'center', marginTop: 10 }}>
//                                                     <TouchableOpacity>
//                                                         <Image source={Images.StartIcon} style={{
//                                                             paddingLeft: 10,
//                                                             width: 20, height: 20, resizeMode: 'contain',
//                                                         }} />
//                                                     </TouchableOpacity>
//                                                     <Text style={{ color: color.primaryColorBlack }}> 4.5</Text>

//                                                 </View>
//                                             </View>
//                                             <View style={{ flexDirection: 'row' }}>
//                                                 <Text style={{ marginTop: 4, fontWeight: 'bold', color: color.primaryColorBlack }}>{item.Description}</Text>
//                                                 <Text style={{ marginTop: 4, color: color.primaryColorBlack }}>{item.Name}</Text>
//                                             </View>

//                                             <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginBottom: 10 }}>
//                                                 <View style={{ flexDirection: "row", width: width * (130 / 375), height: 40, marginTop: 4, justifyContent: 'space-between' }}>
//                                                     <View style={{ flexDirection: "row", width: width * (60 / 375), marginTop: 4, borderRadius: width * (30 / 375), borderWidth: 1, borderColor: color.appTextColor, justifyContent: 'center', alignItems: 'center' }}>
//                                                         <TouchableOpacity>
//                                                             <Image source={Images.GroupIcons} style={{
//                                                                 paddingLeft: 10,
//                                                                 width: 20, height: 20, resizeMode: 'contain',
//                                                             }} />
//                                                         </TouchableOpacity>
//                                                         <TouchableOpacity>
//                                                             <Text style={{ paddingLeft: 10 }}>2</Text>
//                                                         </TouchableOpacity>
//                                                     </View>

//                                                     <View style={{ flexDirection: "row", width: width * (60 / 375), marginTop: 4, borderRadius: width * (30 / 375), borderWidth: 1, borderColor: color.appTextColor, justifyContent: 'center', alignItems: 'center' }}>
//                                                         <TouchableOpacity>
//                                                             <Image source={Images.BedIcons} style={{
//                                                                 paddingLeft: 10,
//                                                                 width: 20, height: 20, resizeMode: 'contain',
//                                                             }} />
//                                                         </TouchableOpacity>
//                                                         <TouchableOpacity>
//                                                             <Text style={{ paddingLeft: 10 }}>2</Text>
//                                                         </TouchableOpacity>

//                                                     </View>

//                                                 </View>
//                                                 <View style={{ backgroundColor: color.appBlueColor, height: 40, width: 40, borderRadius: 20, alignItems: 'center', justifyContent: 'center' }}>
//                                                     <Image source={Images.SeekLogoIcons} style={{

//                                                         width: 20, height: 20, resizeMode: 'contain', justifyContent: 'center', alignContent: 'center'
//                                                     }} />
//                                                 </View>
//                                             </View>

//                                         </View>
//                                     </View>
//                                 </TouchableOpacity>


//                             )
//                         })

//                     }
//                 </View>

//                 <ActionSheet ref={actionSheetRef}  >
//                     <View style={{ height: 700 }}>
//                         <View style={{ borderBottomColor: color.appTextBackgoundColor, borderBottomWidth: 1, marginTop: 20 }}>
//                             <View style={{ flexDirection: 'row', width: width - 30, alignSelf: 'center', justifyContent: 'space-between', bottom: 10 }}>
//                                 <TouchableOpacity>
//                                     <Text style={{ fontSize: 20, fontWeight: '500', color: color.primaryColorBlack }}>Filter</Text>
//                                 </TouchableOpacity>
//                                 <TouchableOpacity onPress={() => actionSheetRef.current.hide()}>
//                                     <Text style={{ color: color.appOrangeColor, fontWeight: '400', fontSize: 16 }}>Clear All</Text>
//                                 </TouchableOpacity>
//                             </View>
//                         </View>
//                         <ScrollView>
//                             <View style={{ marginTop: 8, marginLeft: 15, borderBottomColor: color.appTextBackgoundColor, borderBottomWidth: 1 }}>
//                                 <Text style={{ fontSize: 15, fontWeight: '400', color: color.primaryColorBlack }}>
//                                     TYPE OF ACCOMMODATION
//                                 </Text>
//                                 <View style={{ flexDirection: 'row' }}>
//                                     <View style={{ marginTop: 10, width: width / 2 }}>
//                                         <CheckBoxs Heading={'All'}

//                                         />


//                                     </View>
//                                     <View style={{ marginTop: 10 }}>
//                                         <CheckBoxs Heading={'Residence'}

//                                         />

//                                     </View>
//                                 </View>
//                                 <View style={{ flexDirection: 'row' }}>
//                                     <View style={{ marginTop: 10, width: width / 2 }}>
//                                         <CheckBoxs Heading={'Apartment'} />

//                                     </View>
//                                     <View style={{ marginTop: 10 }}>
//                                         <CheckBoxs Heading={'Villa'} />

//                                     </View>
//                                 </View>
//                                 <View style={{ marginTop: 10, marginBottom: 10 }}>
//                                     <CheckBoxs Heading={'House'} />

//                                 </View>
//                             </View>

//                             <View style={{ marginTop: 8, marginLeft: 15, borderBottomColor: color.appTextBackgoundColor, borderBottomWidth: 1 }}>
//                                 <Text style={{ fontSize: 15, fontWeight: '500', color: color.primaryColorBlack }}>
//                                     CATEGORY
//                                 </Text>
//                                 <View style={{ flexDirection: 'row' }}>
//                                     <View style={{ marginTop: 10, width: width / 2 }}>
//                                         <CheckBoxs Heading={'24/7 electricity'} />

//                                     </View>
//                                     <View style={{ marginTop: 10 }}>
//                                         <CheckBoxs Heading={'Party Homes'} />

//                                     </View>
//                                 </View>
//                                 <View style={{ flexDirection: 'row' }}>
//                                     <View style={{ marginTop: 10, width: width / 2 }}>
//                                         <CheckBoxs Heading={'Inverter'} />

//                                     </View>
//                                     <View style={{ marginTop: 10 }}>
//                                         <CheckBoxs Heading={'Super Host'} />

//                                     </View>
//                                 </View>
//                                 <View style={{ marginTop: 10, marginBottom: 20 }}>
//                                     <CheckBoxs Heading={'Luxury'} />

//                                 </View>
//                             </View>

//                             <View style={{ marginTop: 8, marginLeft: 15, borderBottomColor: color.appTextBackgoundColor, borderBottomWidth: 1, marginBottom: 20 }}>
//                                 <Text style={{ fontSize: 15, fontWeight: '500', color: color.primaryColorBlack }}>
//                                     NUMBER OF BATHROOMS
//                                 </Text>
//                                 <View style={{ flexDirection: 'row', marginTop: 10, marginBottom: 10 }}>
//                                     {Bathroom.map((item, index) => (
//                                         <TouchableOpacity onPress={() => setIsSelect(index)} style={[styles.container, { backgroundColor: isSelect !== index ? 'white' : 'lightgreen' }]}><Text>{item.number}</Text></TouchableOpacity>
//                                     ))}
//                                     {/* <TouchableOpacity onPress={()=>setIsSelect(!isSelect)} style={[styles.container,{borderColor : isSelect ? 'gray':'yellow'}]}><Text>1</Text></TouchableOpacity>
//                             <TouchableOpacity onPress={()=>setIsSelect(!isSelect)} style={[styles.container,{borderColor : isSelect ? 'gray':'yellow'}]}><Text>2</Text></TouchableOpacity>
//                             <TouchableOpacity onPress={()=>setIsSelect(!isSelect)} style={[styles.container,{borderColor : isSelect ? 'gray':'yellow'}]}><Text>3</Text></TouchableOpacity>
//                             <TouchableOpacity onPress={()=>setIsSelect(!isSelect)} style={[styles.container,{borderColor : isSelect ? 'gray':'yellow'}]}><Text>4</Text></TouchableOpacity>
//                             <TouchableOpacity onPress={()=>setIsSelect(!isSelect)} style={[styles.container,{borderColor : isSelect ? 'gray':'yellow'}]}><Text>5</Text></TouchableOpacity> */}
//                                     {/* <TouchableOpacity style={styles.container}><Text>2</Text></TouchableOpacity>
//                             <TouchableOpacity style={styles.container}><Text>3</Text></TouchableOpacity>
//                             <TouchableOpacity style={styles.container}><Text>4</Text></TouchableOpacity>
//                             <TouchableOpacity style={styles.container}><Text>5</Text></TouchableOpacity> */}
//                                 </View>


//                             </View>


//                             <View style={{ marginTop: 8, marginLeft: 15, borderBottomColor: color.appTextBackgoundColor, borderBottomWidth: 1 }}>
//                                 <Text style={{ fontSize: 15, fontWeight: '500', color: color.primaryColorBlack }}>
//                                     MAIN FEATURES
//                                 </Text>
//                                 <View style={{ flexDirection: 'row' }}>
//                                     <View style={{ marginTop: 10, width: width / 2 }}>
//                                         <CheckBoxs Heading={'Swimming pool'} />

//                                     </View>
//                                     <View style={{ marginTop: 10 }}>
//                                         <CheckBoxs Heading={'Closed garage'} />

//                                     </View>
//                                 </View>
//                                 <View style={{ flexDirection: 'row' }}>
//                                     <View style={{ marginTop: 10, width: width / 2 }}>
//                                         <CheckBoxs Heading={'Air conditioning'} />

//                                     </View>
//                                     <View style={{ marginTop: 10 }}>
//                                         <CheckBoxs Heading={'Pet-friendly'} />

//                                     </View>
//                                 </View>
//                                 <View style={{ marginTop: 10, marginBottom: 20 }}>
//                                     <CheckBoxs Heading={'Telivision'} />

//                                 </View>
//                             </View>

//                             <View style={{ marginTop: 8, marginLeft: 15, borderBottomColor: color.appTextBackgoundColor, borderBottomWidth: 1, marginBottom: 20 }}>
//                                 <Text style={{ fontSize: 15, fontWeight: '500', color: color.primaryColorBlack }}>
//                                     Review
//                                 </Text>
//                                 {/* <View style={{ flexDirection: 'row' }}>
//                             <TouchableOpacity onPress={() => setIsSelect(!isSelect)} style={{ flexDirection: 'row', marginTop: 10, marginBottom: 10 }}>
//                                 <Image resizeMode="contain" style={{ height: 25, width: 25, marginRight: 6 }} source={isSelect ? Images.StartIcon : Images.StarIcon} />
//                             </TouchableOpacity>
//                             <TouchableOpacity onPress={() => setIsSelect(!isSelect)} style={{ flexDirection: 'row', marginTop: 10, marginBottom: 10 }}>
//                                 <Image resizeMode="contain" style={{ height: 25, width: 25, marginRight: 6 }} source={isSelect ? Images.StartIcon : Images.StarIcon} />
//                             </TouchableOpacity>
//                             <TouchableOpacity onPress={() => setIsSelect(!isSelect)} style={{ flexDirection: 'row', marginTop: 10, marginBottom: 10 }}>
//                                 <Image resizeMode="contain" style={{ height: 25, width: 25, marginRight: 6 }} source={isSelect ? Images.StartIcon : Images.StarIcon} />
//                             </TouchableOpacity>
//                             <TouchableOpacity onPress={() => setIsSelect(!isSelect)} style={{ flexDirection: 'row', marginTop: 10, marginBottom: 10 }}>
//                                 <Image resizeMode="contain" style={{ height: 25, width: 25, marginRight: 6 }} source={isSelect ? Images.StartIcon : Images.StarIcon} />
//                             </TouchableOpacity>
//                             <TouchableOpacity onPress={() => setIsSelect(!isSelect)} style={{ flexDirection: 'row', marginTop: 10, marginBottom: 10 }}>
//                                 <Image resizeMode="contain" style={{ height: 25, width: 25, marginRight: 6 }} source={isSelect ? Images.StartIcon : Images.StarIcon} />
//                             </TouchableOpacity>
//                         </View> */}
//                                 {/* <Rating
                                        
//                                         ratingImage={Images.StartIcon}
//                                         // ratingColor='#3498db'
//                                         // ratingBackgroundColor='#c8c7c8'
//                                         ratingCount={5}
//                                         imageSize={30}
//                                         // onFinishRating={ratingCompleted}
//                                         style={{  marginHorizontal:10,paddingHorizontal:12}}
//                                     /> */}
//                                 <Rating
//                                     type='star'
//                                     ratingCount={5}
//                                     imageSize={30}
//                                     style={{ margin: 12, marginBottom: 10 }}
//                                     startingValue={0}

//                                 //     showRating
//                                 //     // onFinishRating={this.ratingCompleted}
//                                 />


//                             </View>
//                         </ScrollView>
//                         <TouchableOpacity onPress={() => actionSheetRef.current.hide()} style={{
//                             backgroundColor: color.appBlueColor,
//                             width: width - 30,
//                             padding: 10,
//                             borderRadius: 10,
//                             justifyContent: 'center',
//                             alignSelf: 'center',
//                             height: 50
//                         }}>
//                             <View style={{
//                                 position: 'absolute',
//                                 flex: 1,
//                             }}>
//                                 <Image source={Images.whiteDot} style={{
//                                     paddingLeft: 50,
//                                     width: 25, height: 25, resizeMode: 'contain',
//                                 }} />
//                             </View>

//                             <Text style={{ textAlign: "center", fontSize: width * (20 / 375), color: color.appWhiteColor }}>Filter</Text>
//                         </TouchableOpacity>
//                     </View>
//                 </ActionSheet>
//             </ScrollView>
//             <View style={{marginVertical:-60}}>
//             {/* <BottomTab /> */}
//             <BecomeAhostTab props={props}/>
//             </View>

//             {/* <View style={{ marginBottom: 50 }} /> */}

//         </SafeAreaView>
//     )
// }
// const styles = StyleSheet.create({
//     pagerView: {
//         flex: 1,
//     },
//     container: {
//         marginRight: 10, borderWidth: 1, borderRadius: 5, borderColor: 'gray', width: 25, height: 25, justifyContent: 'center', alignItems: 'center'
//     },
//     topTags: { flexDirection: 'row', height: 25, alignContent: 'center', alignItems: 'center', backgroundColor: color.appWhiteColor, borderRadius: 8, marginTop: 2, marginHorizontal: 4 }
// });


// export default BecomeHostHomeScreen;