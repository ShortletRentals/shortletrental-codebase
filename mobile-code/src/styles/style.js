//Common styles...
import React from "react"
import { Dimensions, Platform, StyleSheet } from "react-native"
import { color } from "./colors";

const {width,height} = Dimensions.get("window");


const commonStyles = StyleSheet.create({
    container:{
        flex:1,
        // backgroundColor:color.appWhiteColor,
        
    },
    rightViewContainer:{
    },
    centeredView: {
        flex: 1,
        justifyContent: "center",
        alignItems: "center",
        marginTop: 22,
        backgroundColor:"rgba(0, 0, 0,0.6)"
      },
      modalView: {
        margin: 20,
        backgroundColor: "white",
        borderRadius: 20,
        padding: 35,
        alignItems: "center",
        shadowColor: "#000",
        shadowOffset: {
          width: 0,
          height: 2
        },
        shadowOpacity: 0.25,
        shadowRadius: 4,
        elevation: 5
      },
      flatView:{
        flexDirection:'column',
        justifyContent:'center',
        alignItems:'center',
        // height:60,
        // width:140,
        paddingVertical:8,
        margin:8,
        borderRadius:8,
        // marginTop:Platform.OS == 'ios'  ? 0 : 0,
        // paddingHorizontal:12,
        // padding:4
        
      },
      flatText:{
        marginHorizontal:6,
        fontWeight:'500',
        color:'#fff',
        marginTop:6
      },
      button: {
        borderRadius: 20,
        padding: 10,
        elevation: 2
      },
      buttonOpen: {
        backgroundColor: "#F194FF",
      },
      buttonClose: {
        backgroundColor: "#2196F3",
      },
      textStyle: {
        color: "white",
        fontWeight: "bold",
        textAlign: "center"
      },
      modalText: {
        marginBottom: 15,
        textAlign: "center"
      }
    
})

export {commonStyles, width, height};