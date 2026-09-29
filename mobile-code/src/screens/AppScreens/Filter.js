import React, { useState } from "react";
import { View, Image, Text, ImageBackground, TouchableOpacity,StyleSheet } from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { color, height, width } from "../../styles/colors";
import Images from "../../styles/Images";
import { commonStyles } from "./../../styles/style";
import CheckBox from '@react-native-community/checkbox';
import CheckBoxs from "../../components/CheckBox";


const Filter = (props) => {
    const [toggleCheckBox, setToggleCheckBox] = useState(false)
    return (
        <View style={commonStyles.container}>
            <SafeAreaView>
                <View style={{ borderBottomColor: color.appTextBackgoundColor, borderBottomWidth: 1, marginTop: 20 }}>
                    <View style={{ flexDirection: 'row', width: width - 30, alignSelf: 'center', justifyContent: 'space-between', bottom: 20 }}>
                        <TouchableOpacity>
                            <Text style={{ fontSize: 20, fontWeight: '500',color:color.primaryColorBlack }}>Filter</Text>
                        </TouchableOpacity>
                        <TouchableOpacity>
                            <Text style={{ color: color.appOrangeColor, fontWeight: '400', fontSize: 16 }}>Clear All</Text>
                        </TouchableOpacity>
                    </View>
                </View>
                <View style={{ marginTop: 8, marginLeft: 15, borderBottomColor: color.appTextBackgoundColor, borderBottomWidth: 1 }}>
                    <Text style={{ fontSize: 15, fontWeight: '500' }}>
                        TYPE OF ACCOMMODATION 
                    </Text>
                    <View style={{ flexDirection: 'row',marginHorizontal:10 }}>
                        <View style={{ marginTop: 10, width: width / 2 }}>
                            <CheckBoxs Heading={'All'} />

                        </View>
                        <View style={{ marginTop: 10 }}>
                            <CheckBoxs Heading={'Residence'} />

                        </View>
                    </View>
                    <View style={{ flexDirection: 'row' }}>
                        <View style={{ marginTop: 10, width: width / 2 }}>
                            <CheckBoxs Heading={'Apartment'} />

                        </View>
                        <View style={{ marginTop: 10 }}>
                            <CheckBoxs Heading={'Villa'} />

                        </View>
                    </View>
                    <View style={{ marginTop: 10, marginBottom: 20 }}>
                        <CheckBoxs Heading={'House'} />

                    </View>
                </View>

                <View style={{ marginTop: 8, marginLeft: 15, borderBottomColor: color.appTextBackgoundColor, borderBottomWidth: 1 }}>
                    <Text style={{ fontSize: 15, fontWeight: '500' }}>
                        CATEGORY
                    </Text>
                    <View style={{ flexDirection: 'row' }}>
                        <View style={{ marginTop: 10, width: width / 2 }}>
                            <CheckBoxs Heading={'24/7 electricity'} />

                        </View>
                        <View style={{ marginTop: 10 }}>
                            <CheckBoxs Heading={'Party Homes'} />

                        </View>
                    </View>
                    <View style={{ flexDirection: 'row' }}>
                        <View style={{ marginTop: 10, width: width / 2 }}>
                            <CheckBoxs Heading={'Inverter'} />

                        </View>
                        <View style={{ marginTop: 10 }}>
                            <CheckBoxs Heading={'Super Host'} />

                        </View>
                    </View>
                    <View style={{ marginTop: 10, marginBottom: 20 }}>
                        <CheckBoxs Heading={'Luxury'} />

                    </View>
                </View>

                <View style={{ marginTop: 8, marginLeft: 15, borderBottomColor: color.appTextBackgoundColor, borderBottomWidth: 1,marginBottom:20 }}>
                    <Text style={{ fontSize: 15, fontWeight: '500' }}>
                        NUMBER OF BATHROOMS
                    </Text>
                    <View style={{ flexDirection: 'row',marginTop:10,marginBottom:10 }}>
                        <View style={styles.container}><Text>1</Text></View>
                        <View style={styles.container}><Text>2</Text></View>
                        <View style={styles.container}><Text>3</Text></View>
                        <View style={styles.container}><Text>4</Text></View>
                        <View style={styles.container}><Text>5</Text></View>
                    </View>


                </View>


                <View style={{ marginTop: 8, marginLeft: 15, borderBottomColor: color.appTextBackgoundColor, borderBottomWidth: 1 }}>
                    <Text style={{ fontSize: 15, fontWeight: '500' }}>
                        MAIN FEATURES
                    </Text>
                    <View style={{ flexDirection: 'row' }}>
                        <View style={{ marginTop: 10, width: width / 2 }}>
                            <CheckBoxs Heading={'Swimming pool'} />

                        </View>
                        <View style={{ marginTop: 10 }}>
                            <CheckBoxs Heading={'Closed garage'} />

                        </View>
                    </View>
                    <View style={{ flexDirection: 'row' }}>
                        <View style={{ marginTop: 10, width: width / 2 }}>
                            <CheckBoxs Heading={'Air conditioning'} />

                        </View>
                        <View style={{ marginTop: 10 }}>
                            <CheckBoxs Heading={'Pet-friendly'} />

                        </View>
                    </View>
                    <View style={{ marginTop: 10, marginBottom: 20 }}>
                        <CheckBoxs Heading={'Telivision'} />

                    </View>
                </View>

                <View style={{ marginTop: 8, marginLeft: 15, borderBottomColor: color.appTextBackgoundColor, borderBottomWidth: 1,marginBottom:20 }}>
                    <Text style={{ fontSize: 15, fontWeight: '500' }}>
                        Review
                    </Text>
                    <View style={{ flexDirection: 'row',marginTop:10,marginBottom:10 }}>
                    <Image resizeMode="contain" style={{ height: 25, width: 25,marginRight:6 }} source={Images.StartIcon} />
                    <Image resizeMode="contain" style={{ height: 25, width: 25,marginRight:6 }} source={Images.StartIcon} />
                    <Image resizeMode="contain" style={{ height: 25, width: 25,marginRight:6 }} source={Images.StartIcon} />
                    <Image resizeMode="contain" style={{ height: 25, width: 25,marginRight:6 }} source={Images.StartIcon} /> 
                    <Image resizeMode="contain" style={{ height: 25, width: 25,marginRight:6 }} source={Images.StartIcon} /> 
                    </View>


                </View>
                <TouchableOpacity onPress={()=>props.navigation.navigate('Logins')} style={{marginTop:20,
                    backgroundColor:color.appBlueColor,
                    width:width-30,
                    padding:10,
                    borderRadius:10,
                    justifyContent:'center',
                    alignSelf:'center'
                }}>
                    <View style={{
                        position:'absolute',
                        flex:1,
                    }}>
                    <Image source={Images.whiteDot} style={{paddingLeft:70,
                        width: 20, height: 20, resizeMode:'contain',
                    }} />
                    </View>

                    <Text style={{textAlign:"center",fontSize: width*(25/375),color:color.appWhiteColor}}>Filter</Text>
                </TouchableOpacity>
            </SafeAreaView>

        </View>
    )
}
const styles = StyleSheet.create({
    container: {
       marginRight:10, borderWidth: 1, borderColor: color.appTextBackgoundColor, width: 25, height: 25, justifyContent: 'center', alignItems: 'center'
    }
})


export default Filter;