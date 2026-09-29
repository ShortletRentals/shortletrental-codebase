import React from "react";
import { View, Image, Text, StyleSheet, SafeAreaView, ImageBackground, TouchableOpacity, StatusBar } from "react-native";
import { color, height, width } from "../styles/colors";
import Images from "../styles/Images";
import { commonStyles } from "../styles/style";
import { Colors, colors } from "react-native/Libraries/NewAppScreen";
import LinearGradient from "react-native-linear-gradient";

const Header = ({ Heading, props, isEdit,onPress }) => {
    
    return (
        <View style={headerStyle.mainContainer}>
            <StatusBar backgroundColor={'transparent'} translucent />
            <LinearGradient colors={[color.appYellowColor, color.appOrangeColor]} style={{ flexDirection: 'row',height: height/9, alignItems: "flex-end"}} >
                <SafeAreaView style={{ flexDirection: "row", justifyContent : 'space-between',marginHorizontal:16 }}>
                    <TouchableOpacity style={{ flex: isEdit ? 0 : 0.5 }}
                    //  onPress={() => props.navigation.goBack()}
                    onPress={onPress}
                     >
                        <Image resizeMode="contain" source={Images.Chevron_Right} style={{
                            width: 25, height: 25, 
                        }} />
                    </TouchableOpacity>

                    <Text style={{ marginBottom: 10, textAlign: 'center', fontSize: 20, fontWeight: '500', color: color.appWhiteColor,alignSelf:'center' }}>{Heading}</Text>
                    {
                        isEdit &&
                        <TouchableOpacity>
                            <Text style={{ fontSize: 20, fontWeight: '500', color: color.appWhiteColor,marginRight:10}} onPress={()=>props.navigation.navigate('EditProfile')}>
                                Edit
                            </Text>
                        </TouchableOpacity>
                    }

                    {/* <Text style={{ marginBottom: 10, textAlign: 'center', fontSize: 20, fontWeight: '500', color: color.appWhiteColor }}>{Heading}</Text> */}
                </SafeAreaView>
                </LinearGradient>
            
        </View>
    )
}

const headerStyle = StyleSheet.create({
    mainContainer: {
        // minHeight: width * (70 / 375),
        //   marginTop:-70,
        justifyContent: 'center',
    }
})

export default Header;