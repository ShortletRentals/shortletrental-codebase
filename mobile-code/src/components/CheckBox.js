
import React, { useState } from "react";
import { View, Image, Text, StyleSheet, SafeAreaView, ImageBackground, TouchableOpacity, StatusBar, Platform } from "react-native";
import { color, height, width } from "../styles/colors";

import CheckBox from '@react-native-community/checkbox';

const CheckBoxs = ({ Heading, props, isEdit, value, onValueChange,boxType }) => {
    const [toggleCheckBox, setToggleCheckBox] = useState(false)
    return (
        <View style={{ flexDirection: 'row',justifyContent:'flex-start', }} >

            <CheckBox
                // tintColor={'#000000'}
                tintColors={{ true: '#F15927', false: '#C1C1C1' }}
                // onFillColor='#007aaf'
                onCheckColor='red'         
                style={{ height: 20, width: 20 ,marginHorizontal:Platform.OS == 'ios' ? 12 :4,transform: Platform.OS == 'ios' ? [{ scaleX:  1 }, { scaleY: 1 }] : [{ scaleX:  1.2 }, { scaleY: 1.2 }],borderRadius:10,borderWidth:1}}
                disabled={false}
                value={toggleCheckBox}
                
                boxType={boxType}
                // lineWidth={2}
                // onAnimationType="bounce"
                
                // value={value}
                
                onValueChange={(newValue) =>{
                     setToggleCheckBox(newValue),
                     onValueChange(newValue)}
                    }
                // onValueChange={onValueChange}
            />
            <Text style={{width:Platform.OS == 'ios' ? width/2-55: width/2-30,  marginBottom: 10,   fontSize: 16, fontWeight: '400', color: color.appTextColor, marginLeft: Platform.OS == 'ios' ? 0 : 12 }}>{Heading}</Text>
        </View>
    )
}



export default CheckBoxs;






//OLD CODE
// import React, { useEffect, useState } from "react";
// import { View, Image, Text, StyleSheet, SafeAreaView, ImageBackground, TouchableOpacity, StatusBar } from "react-native";
// import { color, height, width } from "../styles/colors";

// import CheckBox from '@react-native-community/checkbox';

// const CheckBoxs = ({ Heading, props, isEdit, value, onValueChange, unslectCat, setunslectCat }) => {
//     const [toggleCheckBox, setToggleCheckBox] = useState(false)
//     useEffect(() => {
//         if (unslectCat == true) {
//             setToggleCheckBox(false)
//         }
//         if (toggleCheckBox) {
//             setunslectCat(false)
//         }
//     }, [unslectCat, toggleCheckBox])
//     return (
//         <View style={{ flexDirection: 'row' }} >

//             <CheckBox
//                 // tintColor={'#000000'}
//                 tintColors={{ true: '#F15927', false: '#C1C1C1' }}
//                 // onFillColor='#007aaf'
//                 onCheckColor='red'
//                 style={{ height: 20, width: 20, marginHorizontal: 4, transform: [{ scaleX: 1.2 }, { scaleY: 1.2 }], borderRadius: 10, borderWidth: 1 }}
//                 disabled={false}
//                 value={toggleCheckBox}

//                 // value={value}

//                 onValueChange={(newValue) => {
//                     setToggleCheckBox(newValue),
//                         onValueChange(newValue)
//                     setunslectCat(false)
//                 }
//                 }
//             // onValueChange={onValueChange}
//             />
//             <Text style={{ width: width / 2 - 30, marginBottom: 10, fontSize: 16, fontWeight: '400', color: color.appTextColor, marginLeft: 12 }}>{Heading}</Text>
//         </View>
//     )
// }



// export default CheckBoxs;
