

// import React, {useState, useRef, useEffect} from 'react';
// import {
//   View,
//   Text,
//   ImageBackground,
//   Image,
//   TouchableOpacity,
//   Animated,
// } from 'react-native';
// import { color, width } from '../styles/colors';
// // import font from '../styles/Fonts';

// // import Images from '../styles/Images';
// import Images from '../styles/Images';
// import { height } from '../styles/style';

// const BecomeAhostTab = ({props}) => {
//   const [newCurrentIndex, setNewCurrenrIndex] = useState(0);
//   const fadeAnim = useRef(new Animated.Value(0)).current;
//   const screenArr = ['Home', 'Myfavorites', 'Account', '', ''];

//   const myAnim = index => {
//     var obj = {
//       toValue: index * (width / 5) + 8,
//       velocity: 10,
//       useNativeDriver: true,
//     };
//     Animated.spring(fadeAnim, obj).start();
//   };

//   useEffect(() => {
//     myAnim(0);
//   }, []);

//   return (
//     <View
//       style={{
//         height: height/10-10,
//         width: width,
//         backgroundColor: 'white',
//       }}>
//       <ImageBackground
//         source={Images.footer}
//         style={{height: 61, width: width,marginTop:10}}>
//         <Animated.View
//           style={{
//             width: 60,
//             height: 3,
//             backgroundColor: color.appWhiteColor,
//             transform: [{translateX: fadeAnim}],
//             borderRadius: 10,
            
//           }}
//         />
//         <View
//           style={{
//             flexDirection: 'row',
//             alignItems: 'center',
//             justifyContent: 'space-between',
//             paddingVertical: 10,
//             paddingHorizontal: 20,
//           }}>
//           <TouchableOpacity
//             onPress={() => {setNewCurrenrIndex(0); myAnim(0); props?.navigation?.navigate("BecomeaHostHome")}}
//             style={{alignItems: 'center', zIndex: 1000}}>
//             {newCurrentIndex == 0 ? (
               
//               <Image source={Images.OrangeIcons} 
//               style={{height:20,width:20}}
//               />
//             ) : (
//               <Image
//                 source={Images.SearchIcons}
//               />
//             )}
            
//           </TouchableOpacity>

//           <TouchableOpacity
//             onPress={() => {setNewCurrenrIndex(1); myAnim(1); props?.navigation?.navigate("AddPropertyguest")}}
//             style={{alignItems: 'center', zIndex: 1000}}>
//             {newCurrentIndex == 1 ? (
//               <Image
//               source={Images.plusIcon}
//                style={{height:24,width:24}}
//                resizeMode='contain'
//               />
//             ) : (
//               <Image
//                 source={Images.PlusIcon}
//                 style={{height:24,width:24}}
//                 resizeMode='contain'
               
//               />
//             )}
            
//           </TouchableOpacity>

//           <View
//             style={{
//               position: 'absolute',
//               left: 0,
//               right: 0,
//               top: -60,
//               alignItems: 'center',
//             }}>
//             <View>
//               <TouchableOpacity
//                 activeOpacity={0.8}
//                 // onPress={()=>{setNewCurrenrIndex(2); myAnim(2); props?.navigation?.navigate("BecomeaHost")}}
//                 style={{
                
//                   padding: 0,
//                   borderRadius: 70,
//                 }}>
//                 <Image
//                   source={Images.EyeIcons}
//                   style={{height: 100, width: 100, resizeMode: 'contain'}}
//                 />
//               </TouchableOpacity>
//             </View>
            
//           </View>

//           <View
//             style={{
//               width: 40,
//             }}
//           />

//           <TouchableOpacity
//             // onPress={() =>{ setNewCurrenrIndex(3); myAnim(3); props?.navigation?.navigate("BecomeaHost")}}
//             style={{alignItems: 'center'}}>
//             {newCurrentIndex == 3 ? (
//               <Image
//                 source={Images.notification}
              
//               />
//             ) : (
//               <Image
//                 source={Images.notificationUnselectedIcon}
              
//               />
//             )}
            
//           </TouchableOpacity>

//           <TouchableOpacity
//             // onPress={() =>{ setNewCurrenrIndex(4); myAnim(4), props?.navigation?.navigate("Account")}}
//             style={{alignItems: 'center'}}>
//             {newCurrentIndex == 4 ? (
//               <Image
//                 source={Images.pfofile}
                
//               />
//             ) : (
//               <Image
//                 source={Images.ProfileIcons}
               
//               />
//             )}
            
//           </TouchableOpacity>
//         </View>
//       </ImageBackground>
//     </View>
//   );
// };

// export default BecomeAhostTab;
