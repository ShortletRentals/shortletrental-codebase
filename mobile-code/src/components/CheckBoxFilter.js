import React, { useEffect, useState } from "react";
import { View, Image, Text, StyleSheet, SafeAreaView, ImageBackground, TouchableOpacity, StatusBar, Platform } from "react-native";
import { color, height, width } from "../styles/colors";

import CheckBox from '@react-native-community/checkbox';

const CheckBoxFilter = ({ Heading, props, isEdit, value, onValueChange, unslectCat, setunslectCat }) => {
    const [toggleCheckBox, setToggleCheckBox] = useState(false)
    // useEffect(() => {
    //     if (unslectCat == true) {
    //         setToggleCheckBox(false)
    //     }
    //     if (toggleCheckBox) {
    //         setunslectCat(false)
    //     }
    // }, [unslectCat, toggleCheckBox])
    return (
        <View style={{ flexDirection: 'row' }} >

            <CheckBox
                // tintColor={'#000000'}
                tintColors={{ true: '#F1592A', false: '#C1C1C1' }}
                // onFillColor='#007aaf'
                onCheckColor='#F1592A'
                style={{ height: 20, width: 20, marginBottom: 10,marginLeft:Platform.OS == 'ios' ? 8 : 0, transform:Platform.OS == 'ios' ? [{ scaleX: 1 }, { scaleY: 1}] : [{ scaleX: 1.2 }, { scaleY: 1.2 }],}}
                disabled={false}
                value={toggleCheckBox} 
                onValueChange={(newValue) => {
                    console.log(newValue)
                    setToggleCheckBox(newValue),
                        onValueChange(newValue)
                    setunslectCat(newValue)
                }
                }
            // onValueChange={onValueChange}
            />
            <Text style={{ width: width / 2 - 30, marginBottom: 10, fontSize: 16, fontWeight: '400', color: color.appTextColor, marginLeft: 12 }}>{Heading}</Text>
        </View>
    )
}




export default CheckBoxFilter;
