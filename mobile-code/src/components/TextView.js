import React from "react";
import { View, Image, Text, StyleSheet, onChangeText, TextInput, SafeAreaView } from "react-native";
import { color, height, width } from "../styles/colors";
import Images from "../styles/Images";
import { commonStyles } from "../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";

const TextView = ({
    onChangeText,
    placeholder,
    value,
    isNumeric,
    maxLength,
    secureTextEntry,
    multiline
}) => {
    return (
        <View >
            <SafeAreaView style={{ justifyContent: 'center' }}>
                {/* <TextInput
        style={TextStyle.input}
        onChangeText={onChangeText}
        value={text}
      /> */}
                <TextInput
                    style={TextStyle.input}
                    onChangeText={onChangeText}
                    value={value}
                    placeholder={placeholder}
                    placeholderTextColor={color.appTextColor}
                    keyboardType={isNumeric ? 'numeric' : 'default'}
                    maxLength={maxLength}
                    secureTextEntry={secureTextEntry}
                    multiline={multiline}
                    
                />
            </SafeAreaView>
        </View>
    )
}

const TextStyle = StyleSheet.create({
    input: {
        paddingLeft: 15,
        height: 50,
        margin: 10,
        backgroundColor: color.appTextBackgoundColor,
        borderRadius: 10,
        marginLeft: 20,
        marginRight: 20,
        fontSize: 15,
        color:'#000'
    },
})

export default TextView;