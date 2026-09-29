import React, { useState } from "react";
import { View, Image, Text, ImageBackground, StyleSheet, TextInput, TouchableOpacity, _Text, ScrollView } from "react-native";
import { commonStyles } from "./../../styles/style";
import Header from "../../components/Header";
import { useFocusEffect, useNavigation } from "@react-navigation/native";
import { useEffect } from "react";
import { BackHandler } from "react-native";


const AboutUs = (props) => {
    // const navigation = useNavigation()
    const handler = () => {
        props.navigation.navigate('Setting', { notification: '' })
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
    return (
        <View style={commonStyles.container}>

            <Header Heading={'About Us'} onPress={() => props?.navigation?.navigate('Setting', { notification: '' })} />
            <ScrollView showsVerticalScrollIndicator={false} style={{ marginHorizontal: 24, marginTop: 10 }}>
                <Text style={{ fontSize: 20, fontWeight: '600', color: '#000' }}>Know About Shortlet Rentals</Text>
                <Text style={{ marginTop: 10, color: "#393939", fontSize: 15, textAlign: 'auto' }}>Shortlet Rentals is a multinational company that operates an online marketplace for lodging, primarily short lets for vacation rentals, and tourism activities.

                    Based in both Lagos, Nigeria, & Dallas Texas, the platform is accessible only via website.

                    We connect homeowners, referred to as “Hosts”, with families and vacationers, referred to as “guests”, looking for something more than a hotel for their trip. The platform offers guests an array of rental property types ranging from studio apartments to up to five bedroom homes. It also offers guest party houses, penthouses and Luxury accommodations with amazing features.

                    We ensure all properties on the platform are vetted and verified to ensure listings, pictures and descriptions are the same as real life.

                    We also ensure our hosts get their payout in their local currency to ensure quick and easy access to their funds.

                    All bookings on the platform are guaranteed with our “Book with Confidence” guarantee.

                    Check out our Help center page to learn more.</Text>
                <Text style={{ marginTop: 10, color: "#393939", fontSize: 18, fontWeight: '600' }}>We make finding the perfect short let easy with only 3 steps:</Text>
                <Text style={{ fontSize: 14, fontWeight: '400', marginTop: 10, color: '#000' }}>&#x2022; Browse - Browse through the amazing and verified listings,</Text>
                <Text style={{ fontSize: 14, fontWeight: '400', marginTop: 10, color: '#000' }}>&#x2022; Book - Book with confidence,</Text>
                <Text style={{ fontSize: 14, fontWeight: '400', marginTop: 10, color: '#000' }}>&#x2022; Go - Be on the move to your new rental homes</Text>
                <View style={{ marginBottom: 50 }} />
            </ScrollView>
        </View>
    )
}

export default AboutUs;